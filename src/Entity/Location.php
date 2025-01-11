<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $dureeLocation = null;

    #[ORM\Column]
    private ?float $prixLocation = null;

    #[ORM\Column]
    private ?float $prixLocationRemise = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateLocation = null;

    #[ORM\Column(length: 255)]
    private ?string $etatLocation = null;

    #[ORM\ManyToOne(inversedBy: 'locations')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')] 
    private ?Client $client = null;


    #[ORM\ManyToOne(inversedBy: 'locations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Materiel $materiel = null;

    #[ORM\OneToOne(mappedBy: 'location', cascade: ['persist', 'remove'])]
    private ?Paiement $paiement = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDureeLocation(): ?\DateTimeInterface
    {
        return $this->dureeLocation;
    }

    public function setDureeLocation(\DateTimeInterface $dureeLocation): static
    {
        $this->dureeLocation = $dureeLocation;

        return $this;
    }

    public function getPrixLocation(): ?float
    {
        return $this->prixLocation;
    }

    public function setPrixLocation(float $prixLocation): static
    {
        $this->prixLocation = $prixLocation;

        return $this;
    }

    public function getPrixLocationRemise(): ?float
    {
        return $this->prixLocationRemise;
    }

    public function setPrixLocationRemise(float $prixLocationRemise): static
    {
        $this->prixLocationRemise = $prixLocationRemise;

        return $this;
    }

    public function getDateLocation(): ?\DateTimeInterface
    {
        return $this->dateLocation;
    }

    public function setDateLocation(\DateTimeInterface $dateLocation): static
    {
        $this->dateLocation = $dateLocation;

        return $this;
    }

    public function getEtatLocation(): ?string
    {
        return $this->etatLocation;
    }

    public function setEtatLocation(string $etatLocation): static
    {
        $this->etatLocation = $etatLocation;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getMateriel(): ?Materiel
    {
        return $this->materiel;
    }

    public function setMateriel(?Materiel $materiel): static
    {
        $this->materiel = $materiel;

        return $this;
    }

    public function getPaiement(): ?Paiement
    {
        return $this->paiement;
    }

    public function setPaiement(?Paiement $paiement): static
    {
        // unset the owning side of the relation if necessary
        if ($paiement === null && $this->paiement !== null) {
            $this->paiement->setLocation(null);
        }

        // set the owning side of the relation if necessary
        if ($paiement !== null && $paiement->getLocation() !== $this) {
            $paiement->setLocation($this);
        }

        $this->paiement = $paiement;

        return $this;
    }

}
