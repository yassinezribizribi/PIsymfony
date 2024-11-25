<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\ManyToOne(inversedBy: 'cours')]
    private ?Utilisateur $utilisateur = null;

    /**
     * @var Collection<int, Evaluation>
     */
    #[ORM\OneToMany(targetEntity: Evaluation::class, mappedBy: 'cours')]
    private Collection $evaluations;

    /**
     * @var Collection<int, Chapitre>
     */
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

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
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
            // set the owning side to null (unless already changed)
            if ($evaluation->getCours() === $this) {
                $evaluation->setCours(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Chapitre>
     */
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
            // set the owning side to null (unless already changed)
            if ($chapitre->getCours() === $this) {
                $chapitre->setCours(null);
            }
        }

        return $this;
    }
}
