<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvaluationRepository::class)]
class Evaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titreEvalution = null;

    #[ORM\Column(length: 255)]
    private ?string $noteMaximale = null;

    #[ORM\ManyToOne(inversedBy: 'evaluations')]
    private ?Cours $cours = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreEvalution(): ?string
    {
        return $this->titreEvalution;
    }

    public function setTitreEvalution(string $titreEvalution): static
    {
        $this->titreEvalution = $titreEvalution;

        return $this;
    }

    public function getNoteMaximale(): ?string
    {
        return $this->noteMaximale;
    }

    public function setNoteMaximale(string $noteMaximale): static
    {
        $this->noteMaximale = $noteMaximale;

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
