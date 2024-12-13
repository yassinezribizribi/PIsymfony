<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Tache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $titreTache;

    #[ORM\Column(length: 255)]
    private string $descriptionTache;

    #[ORM\Column(type: "datetime")]
    #[Assert\GreaterThanOrEqual("today", message: "La date de début ne peut pas être inférieure à la date actuelle.")]
    private \DateTimeInterface $dateCreation;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $dateEcheance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $file = null;

    // Define the OneToMany relationship with Commentaire
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'tache')]
    private Collection $commentaires;
    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'taches')]
#[ORM\JoinColumn(nullable: false)]
private ?Utilisateur $utilisateur = null;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection(); // Initialize the collection
    }

    // Getters and setters for the properties...
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreTache(): string
    {
        return $this->titreTache;
    }

    public function setTitreTache(string $titreTache): self
    {
        $this->titreTache = $titreTache;
        return $this;
    }

    public function getDescriptionTache(): string
    {
        return $this->descriptionTache;
    }

    public function setDescriptionTache(string $descriptionTache): self
    {
        $this->descriptionTache = $descriptionTache;
        return $this;
    }

    public function getDateCreation(): \DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getDateEcheance(): ?\DateTimeInterface
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(?\DateTimeInterface $dateEcheance): self
    {
        $this->dateEcheance = $dateEcheance;
        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;
        return $this;
    }

    // Getter for commentaires
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    // Add a comment to the collection
    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setTache($this);
        }

        return $this;
    }

    // Remove a comment from the collection
    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // Set the owning side to null (unless already changed)
            if ($commentaire->getTache() === $this) {
                $commentaire->setTache(null);
            }
        }

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
