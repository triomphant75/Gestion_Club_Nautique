<?php

namespace App\Entity;

use App\Repository\ForfaitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ForfaitRepository::class)]
class Forfait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nombreSeance = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(length: 100)]
    private ?string $typeForfait = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateExpiration = null;

    #[ORM\Column]
    private ?float $prixForfait = null;

    #[ORM\Column]
    private ?float $prixRemiseForfait = null;

    /**
     * @var Collection<int, UtilisationForfait>
     */
    #[ORM\OneToMany(targetEntity: UtilisationForfait::class, mappedBy: 'forfait')]
    private Collection $UtilisationForfaits;

    #[ORM\ManyToOne(inversedBy: 'forfaits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

   

    /**
     * @var Collection<int, Cours>
     */
    #[ORM\ManyToMany(targetEntity: Cours::class, mappedBy: 'forfaits')]
    private Collection $cours;

    #[ORM\OneToOne(mappedBy: 'forfait', cascade: ['persist', 'remove'])]
    private ?Paiement $paiement = null;

    public function __construct()
    {
        $this->UtilisationForfaits = new ArrayCollection();
        $this->cours = new ArrayCollection();
    }





    /*Getters and Setters*/
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreSeance(): ?int
    {
        return $this->nombreSeance;
    }

    public function setNombreSeance(int $nombreSeance): static
    {
        $this->nombreSeance = $nombreSeance;

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

    public function getTypeForfait(): ?string
    {
        return $this->typeForfait;
    }

    public function setTypeForfait(string $typeForfait): static
    {
        $this->typeForfait = $typeForfait;

        return $this;
    }

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(\DateTimeInterface $dateExpiration): static
    {
        $this->dateExpiration = $dateExpiration;

        return $this;
    }

    public function getPrixForfait(): ?float
    {
        return $this->prixForfait;
    }

    public function setPrixForfait(float $prixForfait): static
    {
        $this->prixForfait = $prixForfait;

        return $this;
    }

    public function getPrixRemiseForfait(): ?float
    {
        return $this->prixRemiseForfait;
    }

    public function setPrixRemiseForfait(float $prixRemiseForfait): static
    {
        $this->prixRemiseForfait = $prixRemiseForfait;

        return $this;
    }

    /**
     * @return Collection<int, UtilisationForfait>
     */
    public function getUtilisationForfaits(): Collection
    {
        return $this->UtilisationForfaits;
    }

    public function addUtilisationForfait(UtilisationForfait $utilisationForfait): static
    {
        if (!$this->UtilisationForfaits->contains($utilisationForfait)) {
            $this->UtilisationForfaits->add($utilisationForfait);
            $utilisationForfait->setForfait($this);
        }

        return $this;
    }

    public function removeUtilisationForfait(UtilisationForfait $utilisationForfait): static
    {
        if ($this->UtilisationForfaits->removeElement($utilisationForfait)) {
            // set the owning side to null (unless already changed)
            if ($utilisationForfait->getForfait() === $this) {
                $utilisationForfait->setForfait(null);
            }
        }

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
            $cour->addForfait($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            $cour->removeForfait($this);
        }

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
            $this->paiement->setForfait(null);
        }

        // set the owning side of the relation if necessary
        if ($paiement !== null && $paiement->getForfait() !== $this) {
            $paiement->setForfait($this);
        }

        $this->paiement = $paiement;

        return $this;
    }
}
