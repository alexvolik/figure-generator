<?php

namespace App\Handler;

use App\Entity\EntityChanges;
use Doctrine\ORM\EntityManagerInterface;

class EntityChangesHandler
{
    private $strategies;

    public function __construct(iterable $strategies)
    {
        $this->strategies = $strategies;
    }

    public function revert(EntityChanges $entityChanges)
    {
        foreach ($this->strategies as $strategy) {
            if (!$strategy->supports($entityChanges->getAction())) {
                continue;
            }

            $strategy->revert($entityChanges);
        }
    }
}