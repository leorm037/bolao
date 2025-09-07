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

use App\DTO\PaginacaoDTO;
use App\Entity\Apostador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Apostador>
 */
class ApostadorRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Apostador::class);
    }

    public function save(Apostador $apostador, bool $flush = true): void
    {
        $this->getEntityManager()->persist($apostador);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * 
     * @param int $registrosPorPagina
     * @param int $paginaAtual
     * @return PaginacaoDTO|null
     */
    public function list(int $registrosPorPagina = 10, int $paginaAtual = 1)
    {
        $registros = (!\in_array($registrosPorPagina, [10, 25, 50, 100])) ? 10 : $registrosPorPagina;

        $pagina = ($paginaAtual - 1) * $registrosPorPagina;

        $query = $this->createQueryBuilder('a')
                ->orderBy('a.nome', 'ASC')
                ->setFirstResult($pagina)
                ->setMaxResults($registros)
        ;

        return new PaginacaoDTO(new Paginator($query), $registrosPorPagina, $paginaAtual);
    }

//    /**
//     * @return Apostador[] Returns an array of Apostador objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
//    public function findOneBySomeField($value): ?Apostador
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
