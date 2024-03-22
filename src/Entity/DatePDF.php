<?php

namespace App\Entity;

use App\Repository\DatePDFRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DatePDFRepository::class)]
class DatePDF
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateModif = null;

    #[ORM\Column(nullable: true)]
    private ?int $numMaj = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateModif(): ?\DateTimeInterface
    {
        return $this->dateModif;
    }

    public function setDateModif(?\DateTimeInterface $dateModif): static
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    public function getNumMaj(): ?int
    {
        return $this->numMaj;
    }

    public function setNumMaj(?int $numMaj): static
    {
        $this->numMaj = $numMaj;

        return $this;
    }
}
