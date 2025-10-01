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
use App\Entity\Loteria;
use App\Entity\LoteriaRateio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LoteriaRateio>
 */
class LoteriaRateioRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, LoteriaRateio::class);
    }

    public function list(Loteria $loteria, int $registrosPorPagina = 10, int $paginaAtual = 1) {
        $registros = (!\in_array($registrosPorPagina, [10, 25, 50, 100])) ? 10 : $registrosPorPagina;

        $pagina = ($paginaAtual - 1) * $registrosPorPagina;

        $query = $this->createQueryBuilder('lr')
                ->andWhere('lr.loteria = :loteria')
                ->setParameter('loteria', $loteria)
                ->orderBy('lr.quantidadeDezenasJogadas', 'ASC')
                ->orderBy('lr.quantidadeDezenasAcertadas', 'ASC')
                ->orderBy('lr.quantidadeDezenasPremiadas', 'ASC')
                ->orderBy('lr.quantidadePremios', 'ASC')
                ->setFirstResult($pagina)
                ->setMaxResults($registros)
                ->setCacheable(true)
        ;

        return new PaginacaoDTO(new Paginator($query), $registrosPorPagina, $paginaAtual);
    }

    public function save(LoteriaRateio $loteriaRateio, bool $flush = true): void {
        $this->getEntityManager()->persist($loteriaRateio);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return LoteriaRateio[] Returns an array of LoteriaRateio objects
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
    //    public function findOneBySomeField($value): ?LoteriaRateio
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
