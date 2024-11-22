<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ContenuMessage = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateEnvoi = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Utilisateur $expediteur = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Utilisateur $destinataire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenuMessage(): ?string
    {
        return $this->ContenuMessage;
    }

    public function setContenuMessage(string $ContenuMessage): static
    {
        $this->ContenuMessage = $ContenuMessage;

        return $this;
    }

    public function getDateEnvoi(): ?\DateTimeInterface
    {
        return $this->dateEnvoi;
    }

    public function setDateEnvoi(\DateTimeInterface $dateEnvoi): static
    {
        $this->dateEnvoi = $dateEnvoi;

        return $this;
    }

    public function getExpediteur(): ?Utilisateur
    {
        return $this->expediteur;
    }

    public function setExpediteur(?Utilisateur $expediteur): static
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    public function getDestinataire(): ?Utilisateur
    {
        return $this->destinataire;
    }

    public function setDestinataire(?Utilisateur $destinataire): static
    {
        $this->destinataire = $destinataire;

        return $this;
    }
}
