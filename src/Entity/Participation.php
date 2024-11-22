<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateInscri = null;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    private ?Evenement $evenement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateInscri(): ?\DateTimeInterface
    {
        return $this->dateInscri;
    }

    public function setDateInscri(\DateTimeInterface $dateInscri): static
    {
        $this->dateInscri = $dateInscri;

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

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): static
    {
        $this->evenement = $evenement;

        return $this;
    }
}
