<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categorie>
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    /**
     * @return Categorie[] Returns an array of Categorie objects
     */
    public function searchByTerm(string $term): array
    {
        $query = $this->createQueryBuilder('c');
    
        if ($term === null) {
            return [];
        }
    
        return $this->createQueryBuilder('c')
            ->andWhere('c.nom_categorie LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->orderBy('c.nom_categorie', 'ASC')
            ->getQuery()
            ->getResult();
    }

//    public function findOneBySomeField($value): ?Categorie
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
