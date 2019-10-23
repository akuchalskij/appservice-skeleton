<?php
declare(strict_types=1);

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * Class User
 * @package App\Entity\User
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"user", "customer"})
     * @var int $id
     */
    protected $id;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\Groups({"user", "customer"})
     */
    protected $username;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\Groups({"user", "customer"})
     * @Assert\NotBlank(message="user.blank")
     */
    protected $email;

    /**
     * @var \DateTime|null
     * @Serializer\Type("DateTime")
     * @Serializer\Groups({"user"})
     */
    protected $lastLogin;


    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return parent::getUsername();
    }
}
