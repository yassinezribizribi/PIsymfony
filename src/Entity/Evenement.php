<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titreEven = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptionEven = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateEvenement = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
    private ?Utilisateur $utilisateur = null;

    /**
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'evenement')]
    private Collection $participations;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreEven(): ?string
    {
        return $this->titreEven;
    }

    public function setTitreEven(string $titreEven): static
    {
        $this->titreEven = $titreEven;

        return $this;
    }

    public function getDescriptionEven(): ?string
    {
        return $this->descriptionEven;
    }

    public function setDescriptionEven(string $descriptionEven): static
    {
        $this->descriptionEven = $descriptionEven;

        return $this;
    }

    public function getDateEvenement(): ?\DateTimeInterface
    {
        return $this->dateEvenement;
    }

    public function setDateEvenement(\DateTimeInterface $dateEvenement): static
    {
        $this->dateEvenement = $dateEvenement;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

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
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setEvenement($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getEvenement() === $this) {
                $participation->setEvenement(null);
            }
        }

        return $this;
    }
}
