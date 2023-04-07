<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
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

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message:'Merci d\'entrer un date')]
    private ?\DateTimeInterface $birthday = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Assert\Length(max: 30 ,maxMessage:'Le PAI ne doit pas dépasser 30 caracères.')]
    private ?string $pai = null;

    #[ORM\Column(length: 1000, nullable: true)]
    #[Assert\Length(max: 1000 ,maxMessage:'La description du PAI ne doit pas dépasser 1000 caracères.')]
    private ?string $descriptionPai = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Assert\Length(max: 30 ,maxMessage:'Les allergies ne doivent pas dépasser 30 caracères.')]
    private ?string $allergy = null;

    #[ORM\Column(length: 1000, nullable: true)]
    #[Assert\Length(max: 1000 ,maxMessage:'La description des allergies ne doit pas dépasser 1000 caracères.')]
    private ?string $descriptionAllergy = null;

    #[ORM\Column]
    private ?bool $outdoorGlasses = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $picture = null;

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

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getPai(): ?string
    {
        return $this->pai;
    }

    public function setPai(string $pai): self
    {
        $this->pai = $pai;

        return $this;
    }

    public function getDescriptionPai(): ?string
    {
        return $this->descriptionPai;
    }

    public function setDescriptionPai(string $descriptionPai): self
    {
        $this->descriptionPai = $descriptionPai;

        return $this;
    }

    public function getAllergy(): ?string
    {
        return $this->allergy;
    }

    public function setAllergy(string $allergy): self
    {
        $this->allergy = $allergy;

        return $this;
    }

    public function getDescriptionAllergy(): ?string
    {
        return $this->descriptionAllergy;
    }

    public function setDescriptionAllergy(string $descriptionAllergy): self
    {
        $this->descriptionAllergy = $descriptionAllergy;

        return $this;
    }

    public function isOutdoorGlasses(): ?bool
    {
        return $this->outdoorGlasses;
    }

    public function setOutdoorGlasses(bool $outdoorGlasses): self
    {
        $this->outdoorGlasses = $outdoorGlasses;

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
}
