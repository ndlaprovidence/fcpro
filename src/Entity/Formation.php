<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormationRepository;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
#[ORM\Table(name: "tbl_formation")]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column(nullable: true)]
    private ?int $capacity = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'formations')]
    private ?User $speaker = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'formationsCreatedBy')]
    private ?User $createdBy = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $startDateTime = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endDateTime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image_file_name = null;

    #[ORM\Column(nullable: true)]
    private ?int $capacityMin = null;

    #[ORM\Column(length: 255)]
    private ?string $place = null;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private ?string $objectif = null;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private ?string $prerequis = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $moyenPedagogique = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $evaluation = null;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(?int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSpeaker(): ?User
    {
        return $this->speaker;
    }

    public function setSpeaker(?User $speaker): self
    {
        $this->speaker = $speaker;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

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

    public function getStartDateTime(): ?\DateTimeImmutable
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(?\DateTimeImmutable $startDateTime): self
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    public function getEndDateTime(): ?\DateTimeImmutable
    {
        return $this->endDateTime;
    }

    public function setEndDateTime(?\DateTimeImmutable $endDateTime): self
    {
        $this->endDateTime = $endDateTime;

        return $this;
    }

    public function getImageFileName(): ?string
    {
        return $this->image_file_name;
    }

    public function setImageFileName(?string $image_file_name): self
    {
        $this->image_file_name = $image_file_name;

        return $this;
    }

    public function getCapacityMin(): ?int
    {
        return $this->capacityMin;
    }

    public function setCapacityMin(?int $capacityMin): self
    {
        $this->capacityMin = $capacityMin;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getObjectif(): ?string
    {
        return $this->objectif;
    }

    public function setObjectif(string $objectif): self
    {
        $this->objectif = $objectif;

        return $this;
    }

    public function getPrerequis(): ?string
    {
        return $this->prerequis;
    }

    public function setPrerequis(string $prerequis): self
    {
        $this->prerequis = $prerequis;

        return $this;
    }

    public function getMoyenPedagogique(): ?string
    {
        return $this->moyenPedagogique;
    }

    public function setMoyenPedagogique(string $moyenPedagogique): self
    {
        $this->moyenPedagogique = $moyenPedagogique;

        return $this;
    }

    public function getEvaluation(): ?string
    {
        return $this->evaluation;
    }

    public function setEvaluation(string $evaluation): self
    {
        $this->evaluation = $evaluation;

        return $this;
    }
}
