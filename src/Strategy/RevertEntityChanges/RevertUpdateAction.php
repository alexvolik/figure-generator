<?php

namespace App\Strategy\RevertEntityChanges;

use App\Entity\EntityChanges;
use App\Enum\EntityChangesActionEnum;
use App\ValueObject\ChangedObjectValue;

class RevertUpdateAction extends BaseRevertAction
{
    public function supports(string $action): bool
    {
        return EntityChangesActionEnum::UPDATE === $action;
    }

    public function revert(EntityChanges $entityChanges)
    {
        $repository = $this->getRepository($entityChanges->getEntityClass());

        $entity = $repository->find($entityChanges->getEntityId());

        $reflectionEntityClass = new \ReflectionClass($entity);

        foreach ($entityChanges->getData() as $item) {
            if (!$item instanceof ChangedObjectValue) {
                continue;
            }

            $this->changeFieldValue($reflectionEntityClass, $item, $entity);
        }

        $this->em->persist($entity);
        $this->em->flush($entity);
    }

    private function changeFieldValue(
        \ReflectionClass $reflectionClass,
        ChangedObjectValue $changedObjectValue,
        $entity
    )
    {
        if (!$reflectionClass->hasProperty($changedObjectValue->field)) {
            // todo add logging
            return;
        }

        $property = $reflectionClass->getProperty($changedObjectValue->field);
        $property->setAccessible(true);
        $property->setValue(
            $entity,
            $changedObjectValue->oldValue
        );
    }
}