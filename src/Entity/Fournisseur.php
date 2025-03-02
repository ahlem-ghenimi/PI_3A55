<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use TCPDF;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Mailer\MailerInterface; 
use Symfony\Component\Mime\Email;


#[ORM\Entity(repositoryClass: FournisseurRepository::class)]
class Fournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_fournisseur = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $adresse_fournisseur = null;

    
    /** 
    * @var Collection<int, Produit>
     */
    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'fournisseur')]
    private Collection $produits;

   

    public function __construct()
    {
      
        $this->produits = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFournisseur(): ?string
    {
        return $this->nom_fournisseur;
    }

    public function setNomFournisseur(string $nom_fournisseur): static
    {
        $this->nom_fournisseur = $nom_fournisseur;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresseFournisseur(): ?string
    {
        return $this->adresse_fournisseur;
    }

    public function setAdresseFournisseur(string $adresse_fournisseur): static
    {
        $this->adresse_fournisseur = $adresse_fournisseur;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function getAllLinkedProducts(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setFournisseur($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getFournisseur() === $this) {
                $produit->setFournisseur(null);
            }
        }

        return $this;
    }
   

    public function generatePdf(): void
    {
        // PDF generation logic
    {
        $pdf = new \TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Fournisseur Details', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, 'Name: ' . $this->nom_fournisseur, 0, 1);
        $pdf->Cell(0, 10, 'Email: ' . $this->email, 0, 1);
        $pdf->Cell(0, 10, 'Telephone: ' . $this->telephone, 0, 1);
        $pdf->Cell(0, 10, 'Address: ' . $this->adresse_fournisseur, 0, 1);
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Products:', 0, 1);
        $pdf->SetFont('helvetica', '', 10);
        foreach ($this->produits as $produit) {
            $pdf->Cell(0, 10, $produit->getNomProduit(), 0, 1);
        }
        $pdf->Output('fournisseur.pdf', 'D');
    }
    }
}
