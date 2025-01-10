<?php

namespace App\Entity;

use App\Repository\PanneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanneRepository::class)]
class Panne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datePanne = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebutReparation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFinReparation = null;

    #[ORM\Column(length: 255)]
    private ?string $etatPanne = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'pannes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Materiel $materiel = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDatePanne(): ?\DateTimeInterface
    {
        return $this->datePanne;
    }

    public function setDatePanne(\DateTimeInterface $datePanne): static
    {
        $this->datePanne = $datePanne;

        return $this;
    }

    public function getDateDebutReparation(): ?\DateTimeInterface
    {
        return $this->dateDebutReparation;
    }

    public function setDateDebutReparation(\DateTimeInterface $dateDebutReparation): static
    {
        $this->dateDebutReparation = $dateDebutReparation;

        return $this;
    }

    public function getDateFinReparation(): ?\DateTimeInterface
    {
        return $this->dateFinReparation;
    }

    public function setDateFinReparation(?\DateTimeInterface $dateFinReparation): static
    {
        $this->dateFinReparation = $dateFinReparation;

        return $this;
    }

    public function getEtatPanne(): ?string
    {
        return $this->etatPanne;
    }

    public function setEtatPanne(string $etatPanne): static
    {
        $this->etatPanne = $etatPanne;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

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

}
