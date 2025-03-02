<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column]
    private ?int $quantite_vendue = null;// quantité vendue
    #[ORM\Column(length: 255)]
  
    private ?string $nom_produit = null;

    #[ORM\Column(length: 255)]
    private ?string $cycle_culture = null;

    #[ORM\Column]
    private ?int $quantite_produit = null;

    #[ORM\Column(length: 255)]
    private ?string $unite_quant_prod = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_semis_prod = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_recolte_prod = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $cree_le_prod = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $mis_a_jour_le_prod = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(name: 'categorie_nom', referencedColumnName: 'nom_categorie', nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fournisseur $fournisseur = null;

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nom_produit;
    }

    public function setNomProduit(string $nom_produit): static
    {
        $this->nom_produit = $nom_produit;
        return $this;
    }

    public function getCytcleCulture(): ?string
    {
        return $this->cycle_culture;
    }

    public function setCycleCulture(string $cycle_culture): static
    {
        $this->cycle_culture = $cycle_culture;
        return $this;
    }

    public function getQuantiteProduit(): ?int
    {
        return $this->quantite_produit;
    }

    public function setQuantiteProduit(int $quantite_produit): static
    {
        $this->quantite_produit = $quantite_produit;
        return $this;
    }

    public function getQuantiteVendue(): ?int
    {
        return $this->quantite_vendue;
    }

    public function setQuantiteVendue(int $quantite_vendue): static
    {
        $this->quantite_vendue = $quantite_vendue;
        return $this;
    }

    public function getUniteQuantProd(): ?string
    {
        return $this->unite_quant_prod;
    }

    public function setUniteQuantProd(string $unite_quant_prod): static
    {
        $this->unite_quant_prod = $unite_quant_prod;
        return $this;
    }

    public function getDateSemisProd(): ?\DateTimeInterface
    {
        return $this->date_semis_prod;
    }

    public function setDateSemisProd(?\DateTimeInterface $date_semis_prod): static
    {
        $this->date_semis_prod = $date_semis_prod;
        return $this;
    }

    public function getDateRecolteProd(): ?\DateTimeInterface
    {
        return $this->date_recolte_prod;
    }

    public function setDateRecolteProd(?\DateTimeInterface $date_recolte_prod): static
    {
        $this->date_recolte_prod = $date_recolte_prod;
        return $this;
    }

    public function getCreeLeProd(): ?\DateTimeInterface
    {
        return $this->cree_le_prod;
    }

    public function setCreeLeProd(?\DateTimeInterface $cree_le_prod): static
    {
        $this->cree_le_prod = $cree_le_prod;
        return $this;
    }

    public function getMisAJourLeProd(): ?\DateTimeInterface
    {
        return $this->mis_a_jour_le_prod;
    }

    public function setMisAJourLeProd(?\DateTimeInterface $mis_a_jour_le_prod): static
    {
        $this->mis_a_jour_le_prod = $mis_a_jour_le_prod;
        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): static
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }
}
