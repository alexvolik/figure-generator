<?php

namespace App\Entity;

use Doctrine\Common\Collections\AbstractLazyCollection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use App\Annotation\{HasLazyCollection, LazyCollection};

/**
 * Class Figure
 * @package App\Entity
 *
 * @ORM\Entity
 * @HasLazyCollection
 */
class Figure
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="guid", nullable=true)
     */
    private $batchId;

    /**
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @ORM\Column(type="string")
     */
    private $color;

    /**
     * @LazyCollection(targetEntity="App\Entity\EntityChanges", targetColumnName="entityId")
     */
    private $changes;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct(string $type, string $color, string $batchId = null)
    {
        $this->id = Uuid::uuid4();

        $this->batchId = $batchId;
        $this->type = $type;
        $this->color = $color;

        $this->createdAt = new \DateTime();
    }

    public function getId(): UuidInterface
    {
        return Uuid::fromString($this->id);
    }

    public function getBatchId(): ?string
    {
        return $this->batchId;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getChanges(): AbstractLazyCollection
    {
        return $this->changes;
    }

    public function setChanges(AbstractLazyCollection $changes): self
    {
        $this->changes = $changes;

        return $this;
    }

    public function changeColor()
    {
        $this->color = sprintf('#%06X', random_int(0, 0xFFFFFF));
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}