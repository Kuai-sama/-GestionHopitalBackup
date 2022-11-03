<?php

namespace App\Entity;

use App\Repository\LitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LitRepository::class)]
class Lit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $LitOccupe = null;

    //Many Lits have One Salle. This is the owning side.
    #[ORM\ManyToOne(targetEntity: Salle::class, inversedBy: "lits")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Salle $salle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isLitOccupe(): ?bool
    {
        return $this->LitOccupe;
    }

    public function setLitOccupe(?bool $LitOccupe): self
    {
        $this->LitOccupe = $LitOccupe;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;

        return $this;
    }
}