<?php

namespace App\Entity;

use App\Repository\MaterielRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?float $prixHt = null;

    #[ORM\ManyToOne(inversedBy: 'materiels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TVA $tva = null;

    #[ORM\Column]
    private ?float $prixTtc = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateDeCreation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrixHt(): ?float
    {
        return $this->prixHt;
    }

    public function setPrixHt(float $prixHt): static
    {
        $this->prixHt = $prixHt;

        return $this;
    }

    public function getTva(): ?TVA
    {
        return $this->tva;
    }

    public function setTva(?TVA $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    public function getPrixTtc(): ?float
    {
        return $this->prixTtc;
    }

    public function setPrixTtc(float $prixTtc): static
    {
        $this->prixTtc = $prixTtc;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getDateDeCreation(): ?\DateTimeImmutable
    {
        return $this->dateDeCreation;
    }

    public function setDateDeCreation(\DateTimeImmutable $dateDeCreation): static
    {
        $this->dateDeCreation = $dateDeCreation;

        return $this;
    }
}
