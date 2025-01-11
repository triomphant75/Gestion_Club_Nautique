<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateInscriptionCours = null;

    #[ORM\Column(length: 255)]
    private ?string $statutParticipant = null;

    /**
     * @var Collection<int, UtilisationForfait>
     */
    #[ORM\OneToMany(targetEntity: UtilisationForfait::class, mappedBy: 'participation')]
    private Collection $utilisationForfaits;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete:'CASCADE')]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cours $cours = null;

    public function __construct()
    {
        $this->utilisationForfaits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateInscriptionCours(): ?\DateTimeInterface
    {
        return $this->dateInscriptionCours;
    }

    public function setDateInscriptionCours(\DateTimeInterface $dateInscriptionCours): static
    {
        $this->dateInscriptionCours = $dateInscriptionCours;

        return $this;
    }

    public function getStatutParticipant(): ?string
    {
        return $this->statutParticipant;
    }

    public function setStatutParticipant(string $statutParticipant): static
    {
        $this->statutParticipant = $statutParticipant;

        return $this;
    }

    /**
     * @return Collection<int, UtilisationForfait>
     */
    public function getUtilisationForfaits(): Collection
    {
        return $this->utilisationForfaits;
    }

    public function addUtilisationForfait(UtilisationForfait $utilisationForfait): static
    {
        if (!$this->utilisationForfaits->contains($utilisationForfait)) {
            $this->utilisationForfaits->add($utilisationForfait);
            $utilisationForfait->setParticipation($this);
        }

        return $this;
    }

    public function removeUtilisationForfait(UtilisationForfait $utilisationForfait): static
    {
        if ($this->utilisationForfaits->removeElement($utilisationForfait)) {
            // set the owning side to null (unless already changed)
            if ($utilisationForfait->getParticipation() === $this) {
                $utilisationForfait->setParticipation(null);
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
