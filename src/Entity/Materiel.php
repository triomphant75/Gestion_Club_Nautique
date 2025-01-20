<?php

namespace App\Entity;

use App\Repository\MaterielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
class Materiel
{
    const ETAT_DISPONIBLE = 'Disponible';
    const ETAT_HORS_SERVICE = 'Hors Service';
    const ETAT_LOUE = 'Loué';
    const ETAT_EN_PANNE = 'En Panne';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $typeMateriel = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $caracteristique = null;

    #[ORM\Column(length: 255)]
    private ?string $numSerie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateMiseEnService = null;

    #[ORM\Column(length: 255)]
    private ?string $etatMateriel = null;

    /**
     * @var Collection<int, Cours>
     */
    #[ORM\ManyToMany(targetEntity: Cours::class, mappedBy: 'materiels')]
    private Collection $cours;

    /**
     * @var Collection<int, Location>
     */
    #[ORM\OneToMany(targetEntity: Location::class, mappedBy: 'materiel')]
    private Collection $locations;

    /**
     * @var Collection<int, Panne>
     */
    #[ORM\OneToMany(targetEntity: Panne::class, mappedBy: 'materiel')]
    private Collection $pannes;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->pannes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeMateriel(): ?string
    {
        return $this->typeMateriel;
    }

    public function setTypeMateriel(string $typeMateriel): static
    {
        $this->typeMateriel = $typeMateriel;

        return $this;
    }

    public function getCaracteristique(): ?string
    {
        return $this->caracteristique;
    }

    public function setCaracteristique(string $caracteristique): static
    {
        $this->caracteristique = $caracteristique;

        return $this;
    }

    public function getNumSerie(): ?string
    {
        return $this->numSerie;
    }

    public function setNumSerie(string $numSerie): static
    {
        $this->numSerie = $numSerie;

        return $this;
    }

    public function getDateMiseEnService(): ?\DateTimeInterface
    {
        return $this->dateMiseEnService;
    }

    public function setDateMiseEnService(\DateTimeInterface $dateMiseEnService): static
    {
        $this->dateMiseEnService = $dateMiseEnService;

        return $this;
    }

    public function getEtatMateriel(): ?string
    {
        return $this->etatMateriel;
    }

    public function setEtatMateriel(string $etatMateriel): static
    {
        $this->etatMateriel = $etatMateriel;

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
            $cour->addMateriel($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            $cour->removeMateriel($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): static
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->setMateriel($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getMateriel() === $this) {
                $location->setMateriel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Panne>
     */
    public function getPannes(): Collection
    {
        return $this->pannes;
    }

    public function addPanne(Panne $panne): static
    {
        if (!$this->pannes->contains($panne)) {
            $this->pannes->add($panne);
            $panne->setMateriel($this);
        }

        return $this;
    }

    public function removePanne(Panne $panne): static
    {
        if ($this->pannes->removeElement($panne)) {
            // set the owning side to null (unless already changed)
            if ($panne->getMateriel() === $this) {
                $panne->setMateriel(null);
            }
        }

        return $this;
    }
}
