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
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Apostador>
 */
class ApostadorRepository extends ServiceEntityRepository
{

    public function __construct(private ManagerRegistry $registry) {
        parent::__construct($registry, Apostador::class);
    }

    public function save(Apostador $apostador, bool $flush = true): void {
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
    public function list(Usuario $usuario, int $registrosPorPagina = 10, int $paginaAtual = 1, ?string $filter_nome = null) {
        $registros = (!\in_array($registrosPorPagina, [10, 25, 50, 100])) ? 10 : $registrosPorPagina;

        $pagina = ($paginaAtual - 1) * $registrosPorPagina;

        $query = $this->createQueryBuilder('a')
                ->andWhere('a.usuario = :usuario')
                ->setParameter('usuario', $usuario)
                ->orderBy('a.nome', 'ASC')
                ->setFirstResult($pagina)
                ->setMaxResults($registros)
                ->setCacheable(true)
        ;

        if ($filter_nome) {
            $query
                    ->andWhere('a.nome LIKE :filter_nome')
                    ->setParameter('filter_nome', "%" . $filter_nome . "%")
            ;
        }

        return new PaginacaoDTO(new Paginator($query), $registrosPorPagina, $paginaAtual);
    }

    public function findOneByUuid(Uuid $uuid): ?Apostador {
        return $this->createQueryBuilder('a')
                        ->where('a.uuid = :uuid')
                        ->setParameter('uuid', $uuid->toBinary())
                        ->getQuery()
                        ->getOneOrNullResult()
        ;
    }

    public function delete(Apostador $apostador): void {
        $this->getEntityManager()->remove($apostador);
        $this->getEntityManager()->flush();
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
