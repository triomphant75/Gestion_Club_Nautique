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

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCours = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heureDebutCours = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heureFinCours = null;

    #[ORM\Column(length: 255)]
    private ?string $etatCours = null;

    #[ORM\Column]
    private ?int $nombreDePlace = null;

    /**
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'cours')]
    private Collection $participations;

    /**
     * @var Collection<int, Forfait>
     */
    #[ORM\ManyToMany(targetEntity: Forfait::class, inversedBy: 'cours')]
    private Collection $forfaits;

    /**
     * @var Collection<int, Materiel>
     */
    #[ORM\ManyToMany(targetEntity: Materiel::class, inversedBy: 'cours')]
    private Collection $materiels;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Moniteur $moniteur = null;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Proprietaire $proprietaire = null;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
        $this->forfaits = new ArrayCollection();
        $this->materiels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCours(): ?\DateTimeInterface
    {
        return $this->dateCours;
    }

    public function setDateCours(\DateTimeInterface $dateCours): static
    {
        $this->dateCours = $dateCours;

        return $this;
    }

    public function getHeureDebutCours(): ?\DateTimeInterface
    {
        return $this->heureDebutCours;
    }

    public function setHeureDebutCours(\DateTimeInterface $heureDebutCours): static
    {
        $this->heureDebutCours = $heureDebutCours;

        return $this;
    }

    public function getHeureFinCours(): ?\DateTimeInterface
    {
        return $this->heureFinCours;
    }

    public function setHeureFinCours(\DateTimeInterface $heureFinCours): static
    {
        $this->heureFinCours = $heureFinCours;

        return $this;
    }

    public function getEtatCours(): ?string
    {
        return $this->etatCours;
    }

    public function setEtatCours(string $etatCours): static
    {
        $this->etatCours = $etatCours;

        return $this;
    }

    public function getNombreDePlace(): ?int
    {
        return $this->nombreDePlace;
    }

    public function setNombreDePlace(int $nombreDePlace): static
    {
        $this->nombreDePlace = $nombreDePlace;

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
            $participation->setCours($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getCours() === $this) {
                $participation->setCours(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Forfait>
     */
    public function getForfaits(): Collection
    {
        return $this->forfaits;
    }

    public function addForfait(Forfait $forfait): static
    {
        if (!$this->forfaits->contains($forfait)) {
            $this->forfaits->add($forfait);
        }

        return $this;
    }

    public function removeForfait(Forfait $forfait): static
    {
        $this->forfaits->removeElement($forfait);

        return $this;
    }

    /**
     * @return Collection<int, Materiel>
     */
    public function getMateriels(): Collection
    {
        return $this->materiels;
    }

    public function addMateriel(Materiel $materiel): static
    {
        if (!$this->materiels->contains($materiel)) {
            $this->materiels->add($materiel);
        }

        return $this;
    }

    public function removeMateriel(Materiel $materiel): static
    {
        $this->materiels->removeElement($materiel);

        return $this;
    }

    public function getMoniteur(): ?Moniteur
    {
        return $this->moniteur;
    }

    public function setMoniteur(?Moniteur $moniteur): static
    {
        $this->moniteur = $moniteur;

        return $this;
    }

    public function getProprietaire(): ?Proprietaire
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Proprietaire $proprietaire): static
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }
}
