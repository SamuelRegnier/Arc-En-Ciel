<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:'Merci d\'entrer un nom')]
    #[Assert\Length(max: 30 ,maxMessage:'Le nom ne doit pas dépasser 30 caracères.')]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message:'Merci d\'entrer la date')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255 ,maxMessage:'La description ne doit pas dépasser 255 caracères.')]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Level $level = null;

    #[ORM\ManyToMany(targetEntity: Matter::class, inversedBy: 'tasks')]
    private Collection $matter;

    public function __construct()
    {
        $this->matter = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection<int, Matter>
     */
    public function getMatter(): Collection
    {
        return $this->matter;
    }

    public function addMatter(Matter $matter): self
    {
        if (!$this->matter->contains($matter)) {
            $this->matter->add($matter);
        }

        return $this;
    }

    public function removeMatter(Matter $matter): self
    {
        $this->matter->removeElement($matter);

        return $this;
    }
}
