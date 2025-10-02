<?php

/*
 *     This file is part of Bolão.
 *
 *     (c) Leonardo Rodrigues Marques <leonardo@rodriguesmarques.com.br>
 *
 *     This source file is subject to the MIT license that is bundled
 *     with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Loteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Loteria>
 */
class LoteriaRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Loteria::class);
    }

    /**
     * @return array<int,Loteria>
     */
    public function list()
    {
        return $this->createQueryBuilder('l')
                        ->orderBy('l.nome', 'ASC')
                        ->getQuery()
                        ->setCacheable(true)
                        ->getResult()
        ;
    }

    /**
     * 
     * @return Loteria[]|null
     */
    public function findAllOrderByNome()
    {
        return $this->createQueryBuilder('l')
                        ->orderBy('l.nome', 'ASC')
                        ->getQuery()
                        ->setCacheable(true)
                        ->getResult()
        ;
    }

    //    /**
    //     * @return Loteria[] Returns an array of Loteria objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }
    //    public function findOneBySomeField($value): ?Loteria
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
