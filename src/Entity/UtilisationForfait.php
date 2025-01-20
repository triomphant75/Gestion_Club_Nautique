<?php

namespace App\Entity;

use App\Repository\UtilisationForfaitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisationForfaitRepository::class)]
class UtilisationForfait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateUtilisation = null;

    #[ORM\Column(length: 255)]
    private ?string $statutForfait = null;

    #[ORM\ManyToOne(inversedBy: 'UtilisationForfaits')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Forfait $forfait = null;

    #[ORM\ManyToOne(inversedBy: 'utilisationForfaits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Participation $participation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateUtilisation(): ?\DateTimeImmutable
    {
        return $this->dateUtilisation;
    }

    public function setDateUtilisation(\DateTimeImmutable $dateUtilisation): static
    {
        $this->dateUtilisation = $dateUtilisation;

        return $this;
    }

    public function getStatutForfait(): ?string
    {
        return $this->statutForfait;
    }

    public function setStatutForfait(string $statutForfait): static
    {
        $this->statutForfait = $statutForfait;

        return $this;
    }

    public function getForfait(): ?Forfait
    {
        return $this->forfait;
    }

    public function setForfait(?Forfait $forfait): static
    {
        $this->forfait = $forfait;

        return $this;
    }

    public function getParticipation(): ?Participation
    {
        return $this->participation;
    }

    public function setParticipation(?Participation $participation): static
    {
        $this->participation = $participation;

        return $this;
    }
}
