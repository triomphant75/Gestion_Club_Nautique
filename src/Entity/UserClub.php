<?php

namespace App\Entity;

use App\Repository\UserClubRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserClubRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name:'type')]
#[ORM\DiscriminatorMap(['gestionnaireIT' => "UserClub",
"moniteur"=> "Moniteur",
"compagneProprietaire"=> "CompagneProprietaire",
"proprietaire"=> "Proprietaire"
])]
class UserClub implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 180)]
    protected ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    protected array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    protected ?string $password = null;

    #[ORM\Column(length: 180)]
    protected ?string $prenomUser = null;

    #[ORM\Column(length: 120)]
    protected ?string $adresseUser = null;

    #[ORM\Column(length: 255, unique: true)]
    protected ?string $emailUser = null;

    #[ORM\Column(length: 255)]
    protected ?string $statutUser = null;

    /* Constructeur*/
    
    public function __construct()
    {
     $this->roles[]='ROLE_GESTIONNAIRE';
    }



    /*Getter and Setter*/

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenomUser(): ?string
    {
        return $this->prenomUser;
    }

    public function setPrenomUser(string $prenomUser): static
    {
        $this->prenomUser = $prenomUser;

        return $this;
    }

    public function getAdresseUser(): ?string
    {
        return $this->adresseUser;
    }

    public function setAdresseUser(string $adresseUser): static
    {
        $this->adresseUser = $adresseUser;

        return $this;
    }

    public function getEmailUser(): ?string
    {
        return $this->emailUser;
    }

    public function setEmailUser(string $emailUser): static
    {
        $this->emailUser = $emailUser;

        return $this;
    }

    public function getStatutUser(): ?string
    {
        return $this->statutUser;
    }

    public function setStatutUser(string $statutUser): static
    {
        $this->statutUser = $statutUser;

        return $this;
    }

  

}
