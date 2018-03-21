<?php
/**
 * Created by PhpStorm.
 * User: UTILISATEUR
 * Date: 06/02/2018
 * Time: 11:06
 */

namespace App\Repository;

use App\Entity\Rubrique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;



class RubriqueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Rubrique::class);
    }


}