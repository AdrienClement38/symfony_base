<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "module")]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", nullable: false)]
    private int $id;

    #[ORM\Column(type: "string", length: 100, nullable: false)]
    private string $name;

    #[ORM\Column(type: "text", nullable: false)]
    private string $description;

    #[ORM\ManyToMany(targetEntity: Training::class, mappedBy: "modules")]
    private Collection $trainings;

    public function __construct()
    {
        $this->trainings = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function addTraining(Training $training): self
    {
        $this->trainings[] = $training;
        return $this;
    }

    public function removeTraining(Training $training): void
    {
        $this->trainings->removeElement($training);
    }

    public function getTrainings(): Collection
    {
        return $this->trainings;
    }
}