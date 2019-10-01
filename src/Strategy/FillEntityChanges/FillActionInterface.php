<?php

namespace App\Strategy\FillEntityChanges;

use App\Entity\EntityChanges;

interface FillActionInterface
{
    public function supports(string $action): bool;

    public function fill(string $action, $entity, array $entityChangeSet): EntityChanges;
}