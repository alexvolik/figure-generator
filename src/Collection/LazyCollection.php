<?php

namespace App\Collection;

use Doctrine\Common\Collections\AbstractLazyCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class LazyCollection extends AbstractLazyCollection
{
    protected $em;
    protected $owner;
    protected $targetEntity;
    protected $targetColumnName;

    public function __construct(
        EntityManagerInterface $em,
        $owner,
        string $targetEntity,
        string $targetColumnName
    )
    {
        $this->em = $em;
        $this->owner = $owner;
        $this->targetEntity = $targetEntity;
        $this->targetColumnName = $targetColumnName;
    }

    protected function doInitialize()
    {
        $data = $this->em->getRepository($this->targetEntity)->findBy(
            [
                $this->targetColumnName => $this->owner->getId(),
            ],
            ['createdAt' => 'ASC']
        );

        $this->collection = new ArrayCollection($data);
    }
}