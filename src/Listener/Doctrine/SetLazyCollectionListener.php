<?php

namespace App\Listener\Doctrine;

use App\Service\LazyCollectionService;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class SetLazyCollectionListener
{
    private $lazyCollectionService;

    public function __construct(LazyCollectionService $lazyCollectionService)
    {
        $this->lazyCollectionService = $lazyCollectionService;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        $this->lazyCollectionService->fillLazyCollectionInEntity($entity);
    }
}