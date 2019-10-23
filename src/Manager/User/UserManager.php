<?php
declare(strict_types=1);

namespace App\Manager\User;

use App\Entity\User\BaseUser;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User\User;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\UserManipulator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class UserManager
 * @package App\Manager\User
 */
class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var JWTTokenManagerInterface
     */
    private $jwtManager;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var UserManipulator
     */
    private $userManipulator;

    /**
     * @var TokenStorageInterface $tokenStorage
     */
    private $tokenStorage;

    /**
     * UserManager constructor.
     * @param UserManipulator $userManipulator
     * @param EntityManagerInterface $em
     * @param JWTTokenManagerInterface $jwtManager
     * @param EncoderFactoryInterface $encoderFactory
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        UserManipulator $userManipulator,
        EntityManagerInterface $em,
        JWTTokenManagerInterface $jwtManager,
        EncoderFactoryInterface $encoderFactory,
        TokenStorageInterface $tokenStorage
    ) {
        $this->userManipulator = $userManipulator;
        $this->em = $em;
        $this->jwtManager = $jwtManager;
        $this->encoderFactory = $encoderFactory;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $email
     * @param bool $active
     * @param bool $superadmin
     * @return UserInterface
     */
    public function create(
        string $username,
        string $password,
        string $email,
        bool $active = true,
        bool $superadmin = false
    ): UserInterface {
        $user = $this->userManipulator->create($username, $password, $email, $active, $superadmin);
        return $user;
    }


    /**
     * @param User $user
     * @return User
     */
    public function save(User $user): User
    {
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }


    /**
     * @param User $user
     * @return string
     */
    public function getToken(User $user): string
    {
        return $this->jwtManager->create($user);
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        $token = $this->tokenStorage->getToken();

        if (!($token instanceof TokenInterface)) {
            return null;
        }

        return $token->getUser();
    }

    /**
     * @param User $user
     * @param string|null $password
     * @return bool
     */
    public function isPasswordValid(User $user, string $password = null): bool
    {
        if (is_null($password)) {
            return false;
        }

        return $this->encoderFactory->getEncoder($user)->isPasswordValid($user->getPassword(), $password, '');
    }

    /**
     * @param string $email
     * @return bool
     */
    public function userExists(string $email): bool
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

        return ($user instanceof User);
    }

    /**
     * @param User $user
     * @return BaseUser
     */
    public function removeConfirmationToken(User $user): BaseUser
    {
        $user->setConfirmationToken(null);
        $this->save($user);

        return $user;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getRandomPassword(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '\+/', '___'), '=');
    }

    /**
     * @param User $user
     * @return string
     * @throws \Exception
     */
    public function createConfirmationToken(User $user) : string
    {
        $token = $this->getRandomPassword();
        $user->setConfirmationToken($token);
        $this->save($user);

        return $token;
    }
}