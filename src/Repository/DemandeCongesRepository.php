<?php

namespace App\Repository;

use App\Entity\DemandeConges;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DemandeConges|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeConges|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeConges[]    findAll()
 * @method DemandeConges[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeCongesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DemandeConges::class);
    }

//    /**
//     * @return DemandeConges[] Returns an array of DemandeConges objects
//     */

    public function demandeCongeEnAttente($id)
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.demandeCongeConducteur', 'c') //Joint entité conducteur
            ->select('d')
            ->where('d.statueDemande = :val')
            ->setParameter('val', 1)
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function mesDemandesConges($id)
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.demandeCongeConducteur', 'c') //Joint entité conducteur
            ->select('d')
            ->where('d.statueDemande = :val')
            ->setParameter('val', 0)
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?DemandeConges
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
