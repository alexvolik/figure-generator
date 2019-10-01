<?php

namespace App\Strategy\RevertEntityChanges;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

abstract class BaseRevertAction implements RevertActionInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    protected function getRepository(string $className): ObjectRepository
    {
        return $this->em->getRepository($className);
    }
}