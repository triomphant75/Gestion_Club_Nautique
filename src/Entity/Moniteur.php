<?php

namespace App\Entity;

use App\Repository\MoniteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoniteurRepository::class)]
class Moniteur extends UserClub
{
    

    #[ORM\Column(length: 255)]
    private ?string $diplome = null;

    #[ORM\Column(length: 255)]
    private ?string $specialite = null;

    /**
     * @var Collection<int, Cours>
     */
    #[ORM\OneToMany(targetEntity: Cours::class, mappedBy: 'moniteur')]
    private Collection $cours;


    /* Constructeur*/
        
    public function __construct()
    {
    $this->roles[]='ROLE_MONITEUR';
    $this->cours = new ArrayCollection();
    }


    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(string $diplome): static
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;

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
            $cour->setMoniteur($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getMoniteur() === $this) {
                $cour->setMoniteur(null);
            }
        }

        return $this;
    }
}
