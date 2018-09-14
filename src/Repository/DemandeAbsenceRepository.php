<?php

namespace App\Repository;

use App\Entity\DemandeAbsence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DemandeAbsence|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeAbsence|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeAbsence[]    findAll()
 * @method DemandeAbsence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeAbsenceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DemandeAbsence::class);
    }

//    /**
//     * @return DemandeAbsence[] Returns an array of DemandeAbsence objects
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
    public function findOneBySomeField($value): ?DemandeAbsence
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
