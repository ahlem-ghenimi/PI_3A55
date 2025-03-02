<?php

namespace App\Repository;

use App\Entity\Fournisseur;
use App\Entity\Produit; // Import the Produit entity
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fournisseur>
 *
 * @method Fournisseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fournisseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fournisseur[]    findAll()
 * @method Fournisseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FournisseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fournisseur::class);
    }

    // Add your custom methods here

    public function findProductsByFournisseur(Fournisseur $fournisseur): array
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.produits', 'p')
            ->addSelect('p')
            ->where('f.id = :fournisseurId')
            ->setParameter('fournisseurId', $fournisseur->getId())
            ->getQuery()
            ->getResult();
    }

    public function getMostSoldProduct(): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.quantiteVendue', 'DESC') // Ensure 'quantiteVendue' is a field in the Produit entity
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
