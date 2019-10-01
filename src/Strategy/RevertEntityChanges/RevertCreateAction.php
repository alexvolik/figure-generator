<?php

namespace App\Strategy\RevertEntityChanges;

use App\Entity\EntityChanges;
use App\Enum\EntityChangesActionEnum;

class RevertCreateAction extends BaseRevertAction
{
    public function supports(string $action): bool
    {
        return EntityChangesActionEnum::CREATE === $action;
    }

    public function revert(EntityChanges $entityChanges)
    {
        $repository = $this->getRepository($entityChanges->getEntityClass());

        $entity = $repository->find($entityChanges->getEntityId());

        $this->em->remove($entity);
        $this->em->flush($entity);
    }
}