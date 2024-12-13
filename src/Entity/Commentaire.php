<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "Le contenu du commentaire ne peut pas être vide.")]
    private string $contenu;

    // Add date_commentaire column to entity
    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $dateCommentaire = null;

    // Define the ManyToOne relationship with Tache
    #[ORM\ManyToOne(targetEntity: Tache::class, inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tache $tache = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'commentaires')]
#[ORM\JoinColumn(nullable: false)]
private ?Utilisateur $utilisateur = null;

    // Constructor that sets dateCommentaire to current datetime if not provided
    public function __construct(string $contenu = "")
    {
        $this->contenu = $contenu;
        $this->dateCommentaire = new \DateTime(); // Default value as current date
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function getTache(): ?Tache
    {
        return $this->tache;
    }

    public function setTache(?Tache $tache): self
    {
        $this->tache = $tache;
        return $this;
    }

    public function getDateCommentaire(): ?\DateTimeInterface
    {
        return $this->dateCommentaire;
    }

    public function setDateCommentaire(?\DateTimeInterface $dateCommentaire): self
    {
        $this->dateCommentaire = $dateCommentaire ?? new \DateTime(); // Set to current date if not provided
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
