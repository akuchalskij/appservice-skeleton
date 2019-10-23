<?php
declare(strict_types=1);

namespace App\Service\Util;

use App\Entity\User\BaseUser;
use App\Exceptions\AppExpectedException;

/**
 * Class AppMailer
 * @package App\Service\Util
 */
class AppMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Swift_Message
     */
    private $message;

    /**
     * AppMailer constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param \Swift_Message $message
     */
    public function __construct(
      \Swift_Mailer $mailer,
      \Swift_Message $message
    ) {
        $this->mailer = $mailer;
        $this->message = $message;
    }

    /**
     * @param BaseUser $user
     * @param $text
     * @param $subject
     * @return bool
     * @throws AppExpectedException
     * @throws \Exception
     */
    public function sendEmail(BaseUser $user, $text, $subject)
    {
        $message = clone $this->message;
        $message->setSubject($subject)
          ->setTo($user->getEmail())
          ->setBody($text);

        return $this->send($message);
    }

    /**
     * @param BaseUser $user
     * @param string $data
     * @throws \Exception
     * @return bool
     */
    public function sendRegistrationEmail(BaseUser $user, string $data)
    {
        $message = clone $this->message;
        $message->setSubject('Регистрация')
          ->setTo($user->getEmail())
          ->setBody($data);

        return $this->send($message);
    }

    /**
     * @param BaseUser $user
     * @param string $data
     * @return bool
     * @throws \Exception
     */
    public function sendPasswordRestoreEmail(BaseUser $user, string $data)
    {
        $message = clone $this->message;
        $message->setSubject(
            'Новый пароль'
        )
          ->setTo($user->getEmail())
          ->setBody($data);

        return $this->send($message);
    }

    /**
     * @param \Swift_Message $message
     * @return bool
     * @throws AppExpectedException
     */
    public function send(\Swift_Message $message)
    {
        $failures = '';

        if (!$this->mailer->send($message, $failures)) {
            throw new AppExpectedException($failures);
        }

        return true;
    }
}