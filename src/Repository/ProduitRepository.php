<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function searchByTerm(string $term): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nomProduit LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findBestSellingProduct(): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.quantite_produit', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
