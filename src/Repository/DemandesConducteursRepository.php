<?php

namespace App\Repository;

use App\Entity\DemandesConducteurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DemandesConducteurs|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandesConducteurs|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandesConducteurs[]    findAll()
 * @method DemandesConducteurs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandesConducteursRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DemandesConducteurs::class);
    }

//    /**
//     * @return DemandesConducteurs[] Returns an array of DemandesConducteurs objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DemandesConducteurs
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
