<?php

declare(strict_types=1);

namespace Upservice\UI\Http\Rest\Controller;

use League\Tactician\CommandBus;
use Upservice\UI\Http\Rest\Response\JsonFormatter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommandQueryController extends QueryController
{
    /**
     * @var CommandBus
     */
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus, CommandBus $queryBus, JsonFormatter $formatter, UrlGeneratorInterface $router)
    {
        parent::__construct($queryBus, $formatter, $router);
        $this->commandBus = $commandBus;
    }

    /**
     * @param $command
     */
    protected function exec($command): void
    {
        $this->commandBus->handle($command);
    }


}
