<?php

declare(strict_types=1);

namespace Upservice\Application\Query;

use Broadway\ReadModel\SerializableReadModel;

final class Item
{
    /** @var string */
    public string $id;

    /** @var string */
    public string $type;

    /** @var array */
    public array $resource;

    /** @var array */
    public array $relationships = [];

    /** @var SerializableReadModel */
    public SerializableReadModel $readModel;

    public function __construct(SerializableReadModel $serializableReadModel, array $relations = [])
    {
        $this->id = $serializableReadModel->getId();
        $this->type = $this->type($serializableReadModel);
        $this->resource = $serializableReadModel->serialize();
        $this->relationships = $relations;
        $this->readModel = $serializableReadModel;
    }

    private function type(SerializableReadModel $model): string
    {
        $path = explode('\\', \get_class($model));

        return array_pop($path);
    }
}
