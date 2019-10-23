<?php
declare(strict_types=1);

namespace App\Manager;

class AbstractManager
{
    /**
     * @param $entity
     * @throws \Exception
     */
    public function save($entity)
    {
        if (property_exists($entity, 'createdAt') && property_exists($entity, 'updatedAt')) {
            $now = new \DateTime();
            if (is_null($entity->getCreatedAt())) {
                $entity->setCreatedAt($now);
            }

            $entity->setUpdatedAt($now);
        }
    }
}