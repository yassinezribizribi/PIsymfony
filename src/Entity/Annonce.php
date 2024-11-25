<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titreAnn = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptionAnn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datePublication = null;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    private ?Utilisateur $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreAnn(): ?string
    {
        return $this->titreAnn;
    }

    public function setTitreAnn(string $titreAnn): static
    {
        $this->titreAnn = $titreAnn;

        return $this;
    }

    public function getDescriptionAnn(): ?string
    {
        return $this->descriptionAnn;
    }

    public function setDescriptionAnn(string $descriptionAnn): static
    {
        $this->descriptionAnn = $descriptionAnn;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): static
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
