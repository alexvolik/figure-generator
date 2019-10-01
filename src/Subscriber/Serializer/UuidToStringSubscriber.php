<?php

namespace App\Subscriber\Serializer;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Context;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UuidToStringSubscriber implements SubscribingHandlerInterface
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
                'type' => UuidInterface::class,
                'format' => 'json',
                'method' => 'serializeUuidToJson',
            ],
            [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'type' => UuidInterface::class,
                'format' => 'json',
                'method' => 'deserializeUuidToJson',
            ],
        ];
    }

    public function serializeUuidToJson(JsonSerializationVisitor $visitor, UuidInterface $uuid, array $type, Context $context)
    {
        return $uuid->toString();
    }

    public function deserializeUuidToJson(DeserializationVisitorInterface $visitor, string $guid, array $type, DeserializationContext $context): ArrayCollection
    {
        return Uuid::fromString($guid);
    }
}
