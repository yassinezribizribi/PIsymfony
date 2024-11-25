<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenomUser = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $pwd = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateInscri = null;

    #[ORM\Column(length: 50)]
    #[Assert\Choice(choices: ['etudiant', 'enseignant', 'autre'], message: 'Choisissez un rôle valide.')]
    private string $role;

   
    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'expediteur')]
    private Collection $messages;

    /**
     * @var Collection<int, Cours>
     */
    #[ORM\OneToMany(targetEntity: Cours::class, mappedBy: 'utilisateur')]
    private Collection $cours;

    /**
     * @var Collection<int, Evaluation>
     */
    #[ORM\OneToMany(targetEntity: Evaluation::class, mappedBy: 'utilisateur')]
    private Collection $evaluations;

    /**
     * @var Collection<int, Evenement>
     */
    #[ORM\OneToMany(targetEntity: Evenement::class, mappedBy: 'utilisateur')]
    private Collection $evenements;

    /**
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'utilisateur')]
    private Collection $participations;

    /**
     * @var Collection<int, Annonce>
     */
    #[ORM\OneToMany(targetEntity: Annonce::class, mappedBy: 'utilisateur')]
    private Collection $annonces;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->cours = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
        $this->evenements = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->annonces = new ArrayCollection();
        
    }

    
        public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenomUser(): ?string
    {
        return $this->prenomUser;
    }

    public function setPrenomUser(string $prenomUser): static
    {
        $this->prenomUser = $prenomUser;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPwd(): ?string
    {
        return $this->pwd;
    }

    public function setPwd(string $pwd): static
    {
        $this->pwd = $pwd;

        return $this;
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
    public function getRole(): string
    {
        return $this->role;
    }
    
    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }
    
    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setExpediteur($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getExpediteur() === $this) {
                $message->setExpediteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): static
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getUtilisateur() === $this) {
                $cour->setUtilisateur(null);
            }
        }

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
            $evaluation->setUtilisateur($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): static
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getUtilisateur() === $this) {
                $evaluation->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): static
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->setUtilisateur($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): static
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getUtilisateur() === $this) {
                $evenement->setUtilisateur(null);
            }
        }

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
            $participation->setUtilisateur($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getUtilisateur() === $this) {
                $participation->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Annonce>
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): static
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces->add($annonce);
            $annonce->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): static
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getUtilisateur() === $this) {
                $annonce->setUtilisateur(null);
            }
        }

        return $this;
    }

}