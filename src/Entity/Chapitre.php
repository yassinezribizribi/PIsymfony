<?php

namespace App\Entity;

use App\Repository\ChapitreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChapitreRepository::class)]
class Chapitre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titreChap = null;

    #[ORM\Column(length: 255)]
    private ?string $contenuChap = null;

    #[ORM\ManyToOne(inversedBy: 'chapitres')]
    private ?Cours $cours = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreChap(): ?string
    {
        return $this->titreChap;
    }

    public function setTitreChap(string $titreChap): static
    {
        $this->titreChap = $titreChap;

        return $this;
    }

    public function getContenuChap(): ?string
    {
        return $this->contenuChap;
    }

    public function setContenuChap(string $contenuChap): static
    {
        $this->contenuChap = $contenuChap;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): static
    {
        $this->cours = $cours;

        return $this;
    }
}
