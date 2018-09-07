<?php

namespace App\Repository;

use App\Entity\DemandeAccompte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DemandeAccompte|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeAccompte|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeAccompte[]    findAll()
 * @method DemandeAccompte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeAccompteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DemandeAccompte::class);
    }

//    /**
//     * @return DemandeAccompte[] Returns an array of DemandeAccompte objects
//     */
    public function demandeAccompteEnAttente($id)
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.demandeAccompteConducteur', 'c') //Joint entité conducteur
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

    /*
    public function findOneBySomeField($value): ?DemandeAccompte
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
