<?php

namespace App\Strategy\FillEntityChanges;

use App\Entity\EntityChanges;
use App\Enum\EntityChangesActionEnum;
use App\ValueObject\ChangedObjectValue;
use Ramsey\Uuid\UuidInterface;

class CreateOrUpdateFillAction implements FillActionInterface
{
    public function supports(string $action): bool
    {
        return EntityChangesActionEnum::CREATE === $action || EntityChangesActionEnum::UPDATE === $action;
    }

    public function fill(string $action, $entity, array $entityChangeSet): EntityChanges
    {
        $changes = new EntityChanges(
            $entity->getId(),
            get_class($entity),
            $action
        );

        foreach ($entityChangeSet as $field => $values) {
            foreach ($values as &$value) {
                if ($value instanceof UuidInterface) {
                    $value = $value->toString();
                }
            }

            $changes->addChangedField(
                new ChangedObjectValue($field, $values[0], $values[1])
            );
        }

        return $changes;
    }
}