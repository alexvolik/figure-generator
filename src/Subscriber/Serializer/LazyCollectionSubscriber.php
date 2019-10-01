<?php

namespace App\Subscriber\Serializer;

use App\Collection\LazyCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

final class LazyCollectionSubscriber implements SubscribingHandlerInterface
{
    /**
     * @var bool
     */
    private $initializeExcluded = true;

    public function __construct(bool $initializeExcluded = true)
    {
        $this->initializeExcluded = $initializeExcluded;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'type' => LazyCollection::class,
                'format' => 'json',
                'method' => 'serializeCollection',
            ],
            [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'type' => LazyCollection::class,
                'format' => 'json',
                'method' => 'deserializeCollection',
            ],
        ];
    }

    /**
     * @param SerializationVisitorInterface $visitor
     * @param Collection $collection
     * @param array $type
     * @param SerializationContext $context
     * @return array|\ArrayObject
     */
    public function serializeCollection(SerializationVisitorInterface $visitor, Collection $collection, array $type, SerializationContext $context)
    {
        // We change the base type, and pass through possible parameters.
        $type['name'] = 'array';

        $context->stopVisiting($collection);

        if (false === $this->initializeExcluded) {
            $exclusionStrategy = $context->getExclusionStrategy();
            if (null !== $exclusionStrategy && $exclusionStrategy->shouldSkipClass($context->getMetadataFactory()->getMetadataForClass(\get_class($collection)), $context)) {
                $context->startVisiting($collection);

                return $visitor->visitArray([], $type, $context);
            }
        }
        $result = $visitor->visitArray($collection->toArray(), $type);

        $context->startVisiting($collection);
        return $result;
    }

    /**
     * @param mixed $data
     */
    public function deserializeCollection(DeserializationVisitorInterface $visitor, $data, array $type, DeserializationContext $context): ArrayCollection
    {
        // See above.
        $type['name'] = 'array';

        return new ArrayCollection($visitor->visitArray($data, $type));
    }
}
