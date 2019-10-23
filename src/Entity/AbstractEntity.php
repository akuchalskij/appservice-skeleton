<?php
declare(strict_types=1);

namespace App\Entity;

/**
 * Class AbstractEntity
 * @package App\Entity
 */
abstract class AbstractEntity
{
    /**
     * @param array $params
     * @param AbstractEntity|null $entity
     * @return AbstractEntity
     */
    public static function load(array $params, AbstractEntity $entity = null): AbstractEntity
    {
        if (is_null($entity)) {
            $entity = new static();
        }

        foreach ($params as $param => $value) {
            if ($param == 'id') {
                continue;
            }

            $setter = 'set' . ucfirst($param);
            if (!method_exists($entity, $setter)) {
                continue;
            }

            $entity->{$setter}($value);
        }

        return $entity;
    }

    public function loadOne($param, $value)
    {
        $setter = 'set' . ucfirst($param);
        if (!method_exists($this, $setter)) {
            return;
        }

        $this->{$setter}($value);
    }
}