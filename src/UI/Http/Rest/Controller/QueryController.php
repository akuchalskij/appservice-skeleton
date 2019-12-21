<?php

declare(strict_types=1);

namespace Upservice\UI\Http\Rest\Controller;

use Upservice\Application\Query\Collection;
use Upservice\Application\Query\Item;
use Upservice\UI\Http\Rest\Response\JsonFormatter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use League\Tactician\CommandBus;

abstract class QueryController
{

    private const CACHE_MAX_AGE = 31536000;
    /**
     * @var CommandBus
     */
    private CommandBus $queryBus;
    /**
     * @var JsonFormatter
     */
    private JsonFormatter $formatter;
    /**
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $router;

    /**
     * QueryController constructor.
     * @param CommandBus $queryBus
     * @param JsonFormatter $formatter
     * @param UrlGeneratorInterface $router
     */
    public function __construct(CommandBus $queryBus, JsonFormatter $formatter, UrlGeneratorInterface $router)
    {
        $this->queryBus = $queryBus;
        $this->formatter = $formatter;
        $this->router = $router;
    }

    protected function ask($query)
    {
        return $this->queryBus->handle($query);
    }

    protected function jsonCollection(Collection $collection, bool $isImmutable = false): JsonResponse
    {
        $response = JsonResponse::create($this->formatter::collection($collection));

        $this->decorateWithCache($response, $collection, $isImmutable);

        return $response;
    }

    protected function json(Item $resource): JsonResponse
    {
        return JsonResponse::create($this->formatter->one($resource));
    }

    protected function route(string $name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }

    private function decorateWithCache(JsonResponse $response, Collection $collection, bool $isImmutable): void
    {
        if ($isImmutable && $collection->limit === \count($collection->data)) {
            $response
                ->setMaxAge(self::CACHE_MAX_AGE)
                ->setSharedMaxAge(self::CACHE_MAX_AGE);
        }
    }

}
