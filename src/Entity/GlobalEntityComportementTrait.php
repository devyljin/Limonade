<?php

namespace Agrume\Limonade\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait GlobalEntityComportementTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    protected ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    protected ?\DateTimeInterface $updatedAt = null;

    /**
     * Date the user is "deleted" (soft delete).
     */
    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    protected ?\DateTimeInterface $deletedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $createdBy = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $updatedBy = null;
    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt = new \DateTimeImmutable()): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        if (is_null($this->getCreatedAt())) {
            $this->createdAt = $createdAt;
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(string $createdBy): static
    {
        if (is_null($this->createdBy)) {
            $this->createdBy = $createdBy;
        }

        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(string $updatedBy): static
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }


    #[ORM\PrePersist]
    public function initializeTimestamps(): void
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    #[ORM\PreUpdate]
    public function updateTimestamp(): void
    {
        $this->setUpdatedAt(new \DateTime());
    }


}