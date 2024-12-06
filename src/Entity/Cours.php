<?php
namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotNull;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titreCours = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptionCours = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: false,options: ['default' => 'pdf'])]
    private ?string $type = 'pdf';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contenu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $video = null; // Ajout de la propriété vidéo

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pdf = null; // Ajout de la propriété PDF

    #[ORM\Column(type: "string", length: 50, nullable: false,options: ['default' => 'pdf'])]
    private ?string $typeContenu = 'pdf'; // Type du contenu (PDF, vidéo, etc.)
    
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private $prix;
   

    

    #[ORM\ManyToOne(inversedBy: 'cours')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\OneToMany(targetEntity: Evaluation::class, mappedBy: 'cours')]
    private Collection $evaluations;

    #[ORM\OneToMany(targetEntity: Chapitre::class, mappedBy: 'cours')]
    private Collection $chapitres;

    public function __construct()
    {
        $this->evaluations = new ArrayCollection();
        $this->chapitres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreCours(): ?string
    {
        return $this->titreCours;
    }

    public function setTitreCours(string $titreCours): static
    {
        $this->titreCours = $titreCours;
        return $this;
    }

    public function getDescriptionCours(): ?string
    {
        return $this->descriptionCours;
    }

    public function setDescriptionCours(string $descriptionCours): static
    {
        $this->descriptionCours = $descriptionCours;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): static
    {
        $this->contenu = $contenu;
        return $this;
    }

    // Méthodes pour video

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): static
    {
        $this->video = $video;
        return $this;
    }

    // Méthodes pour pdf

    public function getPdf(): ?string
    {
        return $this->pdf;
    }

    public function setPdf(?string $pdf): static
    {
        $this->pdf = $pdf;
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

    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): static
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations->add($evaluation);
            $evaluation->setCours($this);
        }
        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): static
    {
        if ($this->evaluations->removeElement($evaluation)) {
            if ($evaluation->getCours() === $this) {
                $evaluation->setCours(null);
            }
        }
        return $this;
    }

    public function getChapitres(): Collection
    {
        return $this->chapitres;
    }

    public function addChapitre(Chapitre $chapitre): static
    {
        if (!$this->chapitres->contains($chapitre)) {
            $this->chapitres->add($chapitre);
            $chapitre->setCours($this);
        }
        return $this;
    }

    public function removeChapitre(Chapitre $chapitre): static
    {
        if ($this->chapitres->removeElement($chapitre)) {
            if ($chapitre->getCours() === $this) {
                $chapitre->setCours(null);
            }
        }
        return $this;
    }


    public function getTypeContenu(): ?string
    {
        return $this->typeContenu;
    }

    public function setTypeContenu(string $typeContenu): self
    {
        $this->typeContenu = $typeContenu;
        return $this;
    }

    // Getter et setter pour contenu

    public function getPrix(): ?float
{
    return $this->prix;
}

public function setPrix(float $prix): self
{
    $this->prix = $prix;

    return $this;
}


}
