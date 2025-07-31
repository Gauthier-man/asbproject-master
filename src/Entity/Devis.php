<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

#[ORM\Entity]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $localisation = null;

    #[ORM\Column(length: 255)]
    private ?string $typeProjet = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $surface = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $delai = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;


    #[ORM\Column(length: 50, options: ["default" => "en_cours"])]
    private ?string $status = 'en_cours';

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    // Setters & Getters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
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

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;
        return $this;
    }

    public function getTypeProjet(): ?string
    {
        return $this->typeProjet;
    }

    public function setTypeProjet(string $typeProjet): self
    {
        $this->typeProjet = $typeProjet;
        return $this;
    }

    public function getSurface(): ?string
    {
        return $this->surface;
    }

    public function setSurface(?string $surface): self
    {
        $this->surface = $surface;
        return $this;
    }

    public function getDelai(): ?string
    {
        return $this->delai;
    }

    public function setDelai(?string $delai): self
    {
        $this->delai = $delai;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
