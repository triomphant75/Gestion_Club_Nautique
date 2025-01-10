<?php

namespace App\Entity;

use App\Repository\CampingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampingRepository::class)]
class Camping
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomCamping = null;

    #[ORM\Column(length: 255)]
    private ?string $adresseCamping = null;

    #[ORM\Column(length: 255)]
    private ?string $contactCamping = null;

    #[ORM\Column]
    private ?float $remiseCamping = null;

    /**
     * @var Collection<int, Client>
     */
    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'camping')]
    private Collection $clients;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCamping(): ?string
    {
        return $this->nomCamping;
    }

    public function setNomCamping(string $nomCamping): static
    {
        $this->nomCamping = $nomCamping;

        return $this;
    }

    public function getAdresseCamping(): ?string
    {
        return $this->adresseCamping;
    }

    public function setAdresseCamping(string $adresseCamping): static
    {
        $this->adresseCamping = $adresseCamping;

        return $this;
    }

    public function getContactCamping(): ?string
    {
        return $this->contactCamping;
    }

    public function setContactCamping(string $contactCamping): static
    {
        $this->contactCamping = $contactCamping;

        return $this;
    }

    public function getRemiseCamping(): ?float
    {
        return $this->remiseCamping;
    }

    public function setRemiseCamping(float $remiseCamping): static
    {
        $this->remiseCamping = $remiseCamping;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setCamping($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getCamping() === $this) {
                $client->setCamping(null);
            }
        }

        return $this;
    }
}
