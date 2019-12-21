<?php

declare(strict_types=1);

namespace Upservice\Domain\Core\Specification;

/**
 * Class AbstractSpecification
 * @package Upservice\Domain\Core\Specification
 */
abstract class AbstractSpecification
{
    abstract public function isFitBy($value): bool;

    final public function not($value): bool
    {
        return !$this->isFitBy($value);
    }
}
