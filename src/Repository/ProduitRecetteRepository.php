<?php

namespace App\Repository;

use App\Entity\ProduitRecette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProduitRecette|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitRecette|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitRecette[]    findAll()
 * @method ProduitRecette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProduitRecette::class);
    }

    // /**
    //  * @return ProduitRecette[] Returns an array of ProduitRecette objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProduitRecette
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
