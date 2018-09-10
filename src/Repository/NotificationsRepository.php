<?php

namespace App\Repository;

use App\Entity\Notifications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Notifications|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notifications|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notifications[]    findAll()
 * @method Notifications[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Notifications::class);
    }

//    /**
//     * @return Notifications[] Returns an array of Notifications objects
//     */

    public function recupNotifAttente($id)
    {
        return $this->createQueryBuilder('n')
            ->innerJoin('n.notifConducteur', 'c') //Joint entité conducteur
            ->select('n.id, n.sujetNotif')
            ->where('n.statueNotif = :val')
            ->setParameter('val', 1)
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->orderBy('n.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }



    public function notifDirection()
    {
        return $this->createQueryBuilder('n')
                    ->select('n.sujetNotif, n.dateCreation')
                    ->where('n.notifDirection = :val')
                    ->setParameter('val', 1)
                    ->orderBy('n.id', 'ASC')
                    ->getQuery()
                    ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?Notifications
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
