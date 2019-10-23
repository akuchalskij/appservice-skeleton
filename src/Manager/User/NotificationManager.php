<?php
declare(strict_types=1);

namespace App\Manager\User;

use App\Entity\User\User;
use App\Manager\AbstractManager;
use App\Service\Util\AppMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Environment;

/**
 * Class NotificationManager
 * @package App\Service\Util\Manager
 */
class NotificationManager extends AbstractManager
{
    const STORAGE_LIMIT_IN_MONTH = 12;

    /** @var AppMailer */
    protected $mailer;

    /** @var UserManager */
    protected $userManager;

    /** @var ContainerInterface */
    protected $container;

    /** @var Environment */
    protected $twig;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * NotificationManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param AppMailer $mailer
     * @param UserManager $userManager
     * @param ContainerInterface $container
     * @param Environment $twig
     */
    public function __construct(
      AppMailer $mailer,
      UserManager $userManager,
      ContainerInterface $container,
      Environment $twig,
      EntityManagerInterface $entityManager
    ) {
        $this->mailer = $mailer;
        $this->userManager = $userManager;
        $this->container = $container;
        $this->twig = $twig;
        $this->em = $entityManager;
    }
    /**
     * @param User $user
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public function sendUserPasswordRestoreNotifications(User $user)
    {
        $token = $this->userManager->createConfirmationToken($user);
        $resettingUrl = $this->container->getParameter('resetting_url');
        $link = (string) str_replace('{token}', $token, $resettingUrl);

        $emailData = $this->twig->render(
          'email/passwordRestore.html.twig',
          [
            'name' => $user->getUsername(),
            'link' => $link
          ]
        );
        $this->mailer->sendPasswordRestoreEmail($user, $emailData);
    }
}