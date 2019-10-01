<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\ValueObject\ChangedObjectValue;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 */
class EntityChanges
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    protected $id;

    /**
     * @ORM\Column(type="guid")
     */
    protected $entityId;

    /**
     * @ORM\Column(type="string")
     */
    protected $entityClass;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $action;

    /**
     * @ORM\Column(type="array")
     */
    protected $data = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct(
        UuidInterface $entityId,
        string $entityClass,
        string $action
    )
    {
        $this->id = Uuid::uuid4();

        $this->entityId = $entityId;
        $this->entityClass = $entityClass;
        $this->action = $action;

        $this->createdAt = new \DateTime();
    }

    public function getId(): UuidInterface
    {
        return Uuid::fromString($this->id);
    }

    public function getEntityId(): UuidInterface
    {
        return Uuid::fromString($this->entityId);
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function addChangedField(ChangedObjectValue $changedObjectValue): self
    {
        $this->data[] = $changedObjectValue;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}