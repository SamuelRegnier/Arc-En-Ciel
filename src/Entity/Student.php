<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
#[Vich\Uploadable]
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

    #[ORM\Column(length: 30, nullable: true)]
    #[Assert\NotBlank(message:'Merci d\'entrer un date')]
    private ?string $birthday = null;

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

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $outdoorGlasses = null;

    #[Vich\UploadableField(mapping: 'users', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    private ?Classroom $classroom = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'student')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

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

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(string $birthday): self
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

    public function isOutdoorGlasses(): ?string
    {
        return $this->outdoorGlasses;
    }

    public function setOutdoorGlasses(string $outdoorGlasses): self
    {
        $this->outdoorGlasses = $outdoorGlasses;

        return $this;
    }

   /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getClassroom(): ?Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(?Classroom $classroom): self
    {
        $this->classroom = $classroom;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addStudent($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeStudent($this);
        }

        return $this;
    }
}
