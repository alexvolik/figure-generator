<?php

namespace App\Strategy\FillEntityChanges;

use App\Entity\EntityChanges;
use App\Enum\EntityChangesActionEnum;
use App\ValueObject\ChangedObjectValue;

class RemoveFillAction implements FillActionInterface
{
    public function supports(string $action): bool
    {
        return EntityChangesActionEnum::REMOVE === $action;
    }

    public function fill(string $action, $entity, array $entityChangeSet): EntityChanges
    {
        $changes = new EntityChanges(
            $entity->getId(),
            get_class($entity),
            $action
        );

        $reflectionClass = new \ReflectionClass($entity);

        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $changedField = new ChangedObjectValue(
                $property->getName(),
                $property->getValue($entity),
                null
            );

            $changes->addChangedField($changedField);
        }

        return $changes;
    }
}