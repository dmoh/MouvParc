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

    /**
    * @return DemandeAbsence[] Returns an array of DemandeAbsence objects
    */

    public function demandeAbsenceNonTraitees()
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.statueDemande = :val')
            ->setParameter('val', true)
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }



    public function demandeAbsenceNonTraiteesExploit()
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.statueDemande = :val')
            ->setParameter('val', true)
            ->andWhere('d.statueDemandeExploit = :un')
            ->setParameter('un', true)
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }


    public function demandeAbsenceNonTraiteesRh()
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.statueDemande = :val')
            ->setParameter('val', true)
            ->andWhere('d.statueDemandeExploit = :un')
            ->setParameter('un', false)
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }


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
