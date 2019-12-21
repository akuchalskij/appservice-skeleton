<?php

declare(strict_types=1);

namespace Upservice\Domain\Core\Exception;

/**
 * Class NotFoundException
 * @package Upservice\Domain\Core\Exception
 */
class NotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Resource not found');
    }
}
