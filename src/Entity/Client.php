<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomClient = null;

    #[ORM\Column(length: 255)]
    private ?string $prenomClient = null;

    #[ORM\Column(length: 255)]
    private ?string $adresseClient = null;

    #[ORM\Column(length: 255)]
    private ?string $emailClient = null;

    #[ORM\Column(length: 255)]
    private ?string $niveauClient = null;

    #[ORM\Column(length: 255)]
    private ?string $TelClient = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $DateInscriptionClient = null;

    /**
     * @var Collection<int, Forfait>
     */
    #[ORM\OneToMany(targetEntity: Forfait::class, mappedBy: 'client')]
    private Collection $forfaits;


    
    /**
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'client')]
    private Collection $participations;


    #[ORM\ManyToOne(inversedBy: 'clients')]
    private ?Camping $camping = null;

    /**
     * @var Collection<int, Paiement>
     */
    #[ORM\OneToMany(targetEntity: Paiement::class, mappedBy: 'client')]
    private Collection $paiements;

    /**
     * @var Collection<int, Location>
     */
    #[ORM\OneToMany(targetEntity: Location::class, mappedBy: 'client')]
    private Collection $locations;

    public function __construct()
    {
        $this->forfaits = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->paiements = new ArrayCollection();
        $this->locations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomClient(): ?string
    {
        return $this->nomClient;
    }

    public function setNomClient(string $nomClient): static
    {
        $this->nomClient = $nomClient;

        return $this;
    }

    public function getPrenomClient(): ?string
    {
        return $this->prenomClient;
    }

    public function setPrenomClient(string $prenomClient): static
    {
        $this->prenomClient = $prenomClient;

        return $this;
    }

    public function getAdresseClient(): ?string
    {
        return $this->adresseClient;
    }

    public function setAdresseClient(string $adresseClient): static
    {
        $this->adresseClient = $adresseClient;

        return $this;
    }

    public function getEmailClient(): ?string
    {
        return $this->emailClient;
    }

    public function setEmailClient(string $emailClient): static
    {
        $this->emailClient = $emailClient;

        return $this;
    }

    public function getNiveauClient(): ?string
    {
        return $this->niveauClient;
    }

    public function setNiveauClient(string $niveauClient): static
    {
        $this->niveauClient = $niveauClient;

        return $this;
    }

    public function getTelClient(): ?string
    {
        return $this->TelClient;
    }

    public function setTelClient(string $TelClient): static
    {
        $this->TelClient = $TelClient;

        return $this;
    }

    public function getDateInscriptionClient(): ?\DateTimeImmutable
    {
        return $this->DateInscriptionClient;
    }

    public function setDateInscriptionClient(\DateTimeImmutable $DateInscriptionClient): static
    {
        $this->DateInscriptionClient = $DateInscriptionClient;

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
            $forfait->setClient($this);
        }

        return $this;
    }

    public function removeForfait(Forfait $forfait): static
    {
        if ($this->forfaits->removeElement($forfait)) {
            // set the owning side to null (unless already changed)
            if ($forfait->getClient() === $this) {
                $forfait->setClient(null);
            }
        }

        return $this;
    }

    public function getCamping(): ?Camping
    {
        return $this->camping;
    }

    public function setCamping(?Camping $camping): static
    {
        $this->camping = $camping;

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
            $participation->setClient($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getClient() === $this) {
                $participation->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Paiement>
     */
    public function getPaiements(): Collection
    {
        return $this->paiements;
    }

    public function addPaiement(Paiement $paiement): static
    {
        if (!$this->paiements->contains($paiement)) {
            $this->paiements->add($paiement);
            $paiement->setClient($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): static
    {
        if ($this->paiements->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getClient() === $this) {
                $paiement->setClient(null);
            }
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
            $location->setClient($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getClient() === $this) {
                $location->setClient(null);
            }
        }

        return $this;
    }
}
