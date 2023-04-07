<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:'Merci d\'entrer un nom')]
    #[Assert\Length(max: 30 ,maxMessage:'Le nom ne doit pas dépasser 30 caracères.')]
    private ?string $lastName = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:'Merci d\'entrer un prénom')]
    #[Assert\Length(max: 30 ,maxMessage:'Le prénom ne doit pas dépasser 30 caracères.')]
    private ?string $firstName = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Assert\NotBlank(message:'Merci d\'entrer un email')]
    #[Assert\Length(max: 50 ,maxMessage:'L\'email ne doit pas dépasser 50 caracères.')]
    private ?string $email = null;

    #[ORM\Column(length: 15)]
    #[Assert\NotBlank(message:'Merci d\'entrer un téléphone')]
    #[Assert\Length(max: 15 ,maxMessage:'Le téléphone ne doit pas dépasser 15 caracères.')]
    private ?string $phone = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message:'Merci d\'entrer une adresse')]
    #[Assert\Length(max: 150 ,maxMessage:'L\'adresse ne doit pas dépasser 150 caracères.')]
    private ?string $adress = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $picture = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Merci d\'entrer un mot de passe')]
    #[Assert\Length(max: 255 ,maxMessage:'Le mot de passe ne doit pas dépasser 255 caracères.')]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
