<?php

namespace App\Entity;

use App\Repository\CompagneProprietaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompagneProprietaireRepository::class)]
class CompagneProprietaire extends UserClub
{
     /* Constructeur*/
    
     public function __construct()
     {
      $this->roles[]='ROLE_GESTIONNAIRE';
     }
}
