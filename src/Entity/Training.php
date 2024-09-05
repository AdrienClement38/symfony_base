<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TrainingRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: TrainingRepository::class)]
class Training
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", nullable: false)]
    private int $id;

    #[ORM\Column(type: "string", length: 100, nullable: false)]
    private string $name;

    #[ORM\Column(type: "text", nullable: false)]
    private string $description;

    #[ORM\Column(type: "integer", nullable: false)]
    private int $schoolId;

    #[ORM\ManyToOne(targetEntity: School::class, inversedBy: "trainings")]
    #[ORM\JoinColumn(name: "school_id", referencedColumnName: "id")]
    private ?School $school = null;

    #[ORM\ManyToMany(targetEntity: Module::class, inversedBy: "trainings")]
    #[ORM\JoinTable(name: "training_module")]
    #[ORM\JoinColumn(name: "training_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "module_id", referencedColumnName: "id")]
    private Collection $modules;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
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

    public function setSchoolId(int $schoolId): self
    {
        $this->schoolId = $schoolId;
        return $this;
    }

    public function getSchoolId(): int
    {
        return $this->schoolId;
    }

    public function setSchool(?School $school): self
    {
        $this->school = $school;
        return $this;
    }

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function addModule(Module $module): self
    {
        $this->modules[] = $module;
        return $this;
    }

    public function removeModule(Module $module): void
    {
        $this->modules->removeElement($module);
    }

    public function getModules(): Collection
    {
        return $this->modules;
    }
}
