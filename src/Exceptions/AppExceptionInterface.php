<?php
declare(strict_types=1);

namespace App\Exceptions;

/**
 * Interface AppExceptionInterfaces
 */
interface AppExceptionInterface
{

    /**
     * Получение информации по ошибке
     *
     * @return array
     */
    public function getData() : ?array;

    /**
     * Добавление информации по ошибке
     *
     * @param string $key
     * @param string $value
     */
    public function addData(string $key, string $value) : void;
}
