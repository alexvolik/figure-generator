<?php

namespace App\Strategy\RevertEntityChanges;

use App\Entity\EntityChanges;

interface RevertActionInterface
{
    public function supports(string $action): bool;

    public function revert(EntityChanges $entityChanges);
}