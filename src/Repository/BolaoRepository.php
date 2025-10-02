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
use App\Entity\Bolao;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Bolao>
 */
class BolaoRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bolao::class);
    }

    /**
     * @return PaginacaoDTO|null
     */
    public function list(Usuario $usuario, int $registrosPorPagina = 10, int $paginaAtual = 1, ?string $filter_bolao = null, ?string $filter_loteria = null, ?int $filter_concurso = null, ?bool $filter_apurado = null)
    {
        $registros = (!\in_array($registrosPorPagina, [10, 25, 50, 100])) ? 10 : $registrosPorPagina;

        $pagina = ($paginaAtual - 1) * $registrosPorPagina;

        $query = $this->createQueryBuilder('b')
                ->select('b,c,l')
                ->andWhere('b.usuario = :usuario')
                ->setParameter('usuario', $usuario)
                ->innerJoin('b.concurso', 'c', Join::WITH, 'b.concurso = c.id')
                ->innerJoin('c.loteria', 'l', Join::WITH, 'c.loteria = l.id')
                ->addOrderBy('l.nome', 'ASC')
                ->addOrderBy('c.numero', 'DESC')
                ->addOrderBy('b.nome', 'ASC')
                ->setFirstResult($pagina)
                ->setMaxResults($registros)
        ;

        if ($filter_bolao) {
            $query
                    ->andWhere('MATCH (b.nome) AGAINST (:filter_bolao IN BOOLEAN MODE) > 0')
                    ->setParameter('filter_bolao', sprintf('%s*',$filter_bolao))
            ;
        }
        
        if ($filter_loteria) {
            $uuid_filter_loteria = Uuid::fromString($filter_loteria);
            
            $query
                    ->andWhere('l.uuid = :filter_loteria')
                    ->setParameter('filter_loteria', $uuid_filter_loteria->toBinary())
            ;
        }
        
        if ($filter_concurso) {
            $query
                    ->andWhere('c.numero = :filter_concurso')
                    ->setParameter('filter_concurso', $filter_concurso)
            ;
        }
        
        if (null !== $filter_apurado) {
            ($filter_apurado) ? $query->andWhere('c.apuracao IS NOT NULL') : $query->andWhere('c.apuracao IS NULL');
        }

        return new PaginacaoDTO(new Paginator($query), $registrosPorPagina, $paginaAtual);
    }

    //    /**
    //     * @return Bolao[] Returns an array of Bolao objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }
    //    public function findOneBySomeField($value): ?Bolao
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
