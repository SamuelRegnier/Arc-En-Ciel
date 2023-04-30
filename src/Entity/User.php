<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]
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

    #[Vich\UploadableField(mapping: 'users', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Merci d\'entrer un mot de passe')]
    #[Assert\Length(max: 255 ,maxMessage:'Le mot de passe ne doit pas dépasser 255 caracères.')]
    private ?string $password = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Classroom $classroom = null;

    #[ORM\ManyToMany(targetEntity: Student::class, inversedBy: 'users')]
    private Collection $student;

    public function __construct()
    {
        $this->student = new ArrayCollection();
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


    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getClassroom(): ?Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(?Classroom $classroom): self
    {
        // unset the owning side of the relation if necessary
        if ($classroom === null && $this->classroom !== null) {
            $this->classroom->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($classroom !== null && $classroom->getUser() !== $this) {
            $classroom->setUser($this);
        }

        $this->classroom = $classroom;

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudent(): Collection
    {
        return $this->student;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->student->contains($student)) {
            $this->student->add($student);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        $this->student->removeElement($student);

        return $this;
    }
}
