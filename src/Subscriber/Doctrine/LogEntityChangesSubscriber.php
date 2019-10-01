<?php

namespace App\Subscriber\Doctrine;

use App\Entity\EntityChanges;
use App\Enum\EntityChangesActionEnum;
use App\Service\LazyCollectionService;
use App\Strategy\FillEntityChanges\FillActionInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class LogEntityChangesSubscriber implements EventSubscriber
{
    private $strategies;
    private $lazyCollectionService;

    public function __construct(iterable $strategies, LazyCollectionService $lazyCollectionService)
    {
        $this->strategies = $strategies;
        $this->lazyCollectionService = $lazyCollectionService;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->handle($args, EntityChangesActionEnum::CREATE);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->handle($args, EntityChangesActionEnum::UPDATE);
    }

    public function postRemove(LifecycleEventArgs $args)
    {

    }

    private function handle(LifecycleEventArgs $args, string $action)
    {
        $entity = $args->getObject();
        // todo add entity annotation for enabling entity log
        if ($entity instanceof EntityChanges) {
            return;
        }

        $om = $args->getObjectManager();
        $uow = $om->getUnitOfWork();

        $strategy = $this->getFillStrategy($action);

        $changes = $strategy->fill($action, $entity, $uow->getEntityChangeSet($entity));

        $this->lazyCollectionService->fillLazyCollectionInEntity($entity);

        $om->persist($changes);
        $om->flush($changes);
    }

    private function getFillStrategy(string $action): FillActionInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($action)) {
                return $strategy;
            }
        }

        throw new \Exception(sprintf('Undefined log action: %s', $action));
    }
}