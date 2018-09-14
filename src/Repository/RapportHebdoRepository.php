<?php

namespace App\Repository;

use App\Entity\RapportHebdo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RapportHebdo|null find($id, $lockMode = null, $lockVersion = null)
 * @method RapportHebdo|null findOneBy(array $criteria, array $orderBy = null)
 * @method RapportHebdo[]    findAll()
 * @method RapportHebdo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RapportHebdoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RapportHebdo::class);
    }

//    /**
//     * @return RapportHebdo[] Returns an array of RapportHebdo objects
//     */
    /**/
    public function demandesEnAttente($id)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.rapportConducteur', 'c')
            ->select('r.statuDemande')
            ->where('r.statuDemande = :un')
            ->setParameter('un', 1)
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function rapportsHebdo($id)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.rapportConducteur', 'c')
            ->select('r')
            ->where('r.statuDemande = :un')
            ->setParameter('un', 1)
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function toutrapportsNonVuParDirection()
    {
        return $this->createQueryBuilder('r')
                    ->select('r')
                    ->where('r.rapportVuDirection = :zero')
                    ->setParameter('zero', 0)
                    ->getQuery()
                    ->getResult()
        ;
    }

    public function rapportsNonVuDirection($nomConducteur, $prenomConducteur)
    {
        return $this->createQueryBuilder('r')
                ->innerJoin('r.rapportConducteur', 'c')
                ->select('r')
                ->where('r.rapportVuDirection = :zero')
                ->setParameter('zero', 0 )
                ->andWhere('c.nomConducteur = :nomConducteur')
                ->setParameter('nomConducteur', $nomConducteur)
                ->andWhere('prenomConducteur = :prenomConducteur')
                ->setParameter('prenomConducteur', $prenomConducteur)
                ->orderBy('r.dateReclame', 'DESC')
                ->getQuery()
                ->getResult()
            ;
    }


    public function mesRapportsHebdo($id)
    {
        return $this->createQueryBuilder('r')
                    ->innerJoin('r.rapportConducteur', 'c')
                    ->select('r')
                    ->where('c.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getResult()
        ;
    }




    /*
    public function findOneBySomeField($value): ?RapportHebdo
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
