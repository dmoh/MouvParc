<?php

namespace App\Repository;

use App\Entity\QuestionsPaie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QuestionsPaie|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionsPaie|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionsPaie[]    findAll()
 * @method QuestionsPaie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionsPaieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QuestionsPaie::class);
    }



    public function questionsPaieNonTraite()
    {
        return  $this   ->createQueryBuilder('q')
                        ->andWhere('q.statueDemandeDirection = :val')
                        ->setParameter('val', true)
                        ->orderBy('q.id', 'ASC')
                        ->getQuery()
                        ->getResult()
            ;

    }

//    /**
//     * @return QuestionsPaie[] Returns an array of QuestionsPaie objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuestionsPaie
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
