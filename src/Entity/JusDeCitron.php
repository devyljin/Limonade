<?php
namespace Agrume\LimonadeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class JusDeCitron
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $origine = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrigine(): ?string
    {
        return $this->origine;
    }

    public function setOrigine(string $origine): self
    {
        $this->origine = $origine;
        return $this;
    }
}
