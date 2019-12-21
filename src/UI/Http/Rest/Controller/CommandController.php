<?php

declare(strict_types=1);

namespace Upservice\UI\Http\Rest\Controller;

use League\Tactician\CommandBus;

abstract class CommandController
{
    /**
     * @var CommandBus
     */
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    protected function exec($command): void
    {
        $this->commandBus->handle($command);
    }
}
