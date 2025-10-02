<?php

/*
 *     This file is part of Bolão.
 *
 *     (c) Leonardo Rodrigues Marques <leonardo@rodriguesmarques.com.br>
 *
 *     This source file is subject to the MIT license that is bundled
 *     with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Bolao;
use App\Entity\Usuario;
use App\Repository\BolaoRepository;
use App\Repository\LoteriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/bolao', name: 'app_bolao_')]
final class BolaoController extends AbstractController
{

    public function __construct(
            private BolaoRepository $repository,
            private LoteriaRepository $loteriaRepository
    )
    {
        
    }

    #[Route('', name: 'index')]
    public function index(Request $request): Response
    {
        $registrosPorPagina = $request->get('registros-por-pagina', 10);

        $pagina = $request->get('pagina', 1);

        $filter_bolao = $request->get('filter_bolao', null);
        $filter_bolao_sanitized = ('' !== $filter_bolao) ? $filter_bolao : null;

        $filter_concurso = $request->get('filter_concurso', null);
        $filter_concurso_sanitized = ('' !== $filter_concurso) ? $filter_concurso : null;

        $filter_loteria = $request->get('filter_loteria', null);
        $filter_loteria_sanitized = ('' !== $filter_loteria) ? $filter_loteria : null;

        $filter_apurado = $request->get('filter_apurado', null);
        $filter_apurado_sanitized = ('' !== $filter_apurado) ? $filter_apurado : null;

        /** @var Usuario $usuario */
        $usuario = $this->getUser();

        $boloes = $this->repository->list(
                $usuario,
                $registrosPorPagina,
                $pagina,
                $filter_bolao_sanitized,
                $filter_loteria_sanitized,
                $filter_concurso_sanitized,
                $filter_apurado_sanitized
        );

        $loterias = $this->loteriaRepository->findAllOrderByNome();

        return $this->render('bolao/index.html.twig', [
                    'boloes' => $boloes,
                    'loterias' => $loterias,
                    'filter_concurso' => $filter_concurso,
                    'filter_loteria' => $filter_loteria,
                    'filter_bolao' => $filter_bolao_sanitized,
                    'filter_apurado' => $filter_apurado
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        return $this->render();
    }

    #[Route('/{uuid:bolao}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['uuid' => '[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}'])]
    public function edit(Bolao $bolao, Request $request): Response
    {
        return $this->render();
    }

    #[Route('/delete', name: 'delete', methods: ['POST'])]
    public function delete(Request $request): Response
    {
        return $this->redirectToRoute();
    }
}
