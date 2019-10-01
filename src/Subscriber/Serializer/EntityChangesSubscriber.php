<?php

namespace App\Subscriber\Serializer;

use App\Entity\EntityChanges;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class EntityChangesSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
            ],
        ];
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        $object = $event->getObject();
        if (!$object instanceof EntityChanges) {
            return;
        }

        $visitor = $event->getVisitor();
        if (!empty($visitor->getData['data'])) {
            $visitor->setData('data', $object->getData()->toArray());
        }
    }
}
