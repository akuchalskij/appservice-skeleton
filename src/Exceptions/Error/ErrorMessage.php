<?php
declare(strict_types=1);

namespace App\Exceptions\Error;

/**
 * Interface ErrorMessage
 * @package App\Exceptions\Error
 */
interface ErrorMessage
{
    const USER_BAD_CREDENTIALS  = 'Неверный пароль или email';
    const USER_EMAIL_IS_EXIST   = "Такой email зарегистрирован!";
    const USER_PROFILE_DISABLED = "Ваш профиль заблокирован";
}