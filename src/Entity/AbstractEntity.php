<?php

namespace Agrume\Limonade\Entity;

use Agrume\Limonade\Entity\GlobalEntityComportementTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

abstract class AbstractEntity
{
    use GlobalEntityComportementTrait;

    public function __construct(){
        $this->setId(Uuid::v4());
    }

    /**
     * Primary Key (UUID).
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    protected ?Uuid $id = null;

    private function setId(?Uuid $id): void
    {
        $this->id = $id;
    }
    public function getId(): ?Uuid
    {
        return $this->id;
    }
}