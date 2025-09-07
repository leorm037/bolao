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

use App\Entity\Apostador;
use App\Form\ApostadorFormType;
use App\Repository\ApostadorRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/apostador', name: 'app_apostador_')]
final class ApostadorController extends AbstractController {

    public function __construct(
            private ApostadorRepository $repository
    ) {
        
    }

    #[Route('', name: 'index')]
    public function index(Request $request): Response {
        $registrosPorPaginas = $request->get('registros-por-pagina', 10);

        $pagina = $request->get('pagina', 1);

        $filter_nome = $request->get('filter_nome', null);
        $filter_nome_sanitized = ('' !== $filter_nome) ? $filter_nome : null;

        $usuario = $this->getUser();

        $apostadores = $this->repository->list(
                $usuario,
                $registrosPorPaginas,
                $pagina,
                $filter_nome_sanitized
        );

        return $this->render('apostador/index.html.twig', [
                    'apostadores' => $apostadores,
                    'filter_nome' => $filter_nome_sanitized
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response {
        $apostador = new Apostador();

        $form = $this->createForm(ApostadorFormType::class, $apostador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuario = $this->getUser();

            $apostador->setUsuario($usuario);

            try {
                $this->repository->save($apostador);

                $this->addFlash('success', \sprintf('Apostador "%s" foi cadastrado com sucesso.', $apostador->getNome()));
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('danger', \sprintf('Apostador "%s" já está cadastrado.', $apostador->getNome()));
            }

            return $this->redirectToRoute('app_apostador_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('apostador/new.html.twig', ['form' => $form]);
    }

    #[Route('/{uuid:apostador}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['uuid' => '[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}'])]
    #[IsGranted('APOSTADOR_EDIT', 'apostador')]
    public function edit(Request $request, Apostador $apostador): Response {
        $form = $this->createForm(ApostadorFormType::class, $apostador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->repository->save($apostador);

                $this->addFlash('success', \sprintf('Apostador "%s" foi alterado com sucesso.', $apostador->getNome()));
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('danger', \sprintf('Apostador "%s" com o mesmo nome já está cadastrado.', $apostador->getNome()));
            }

            return $this->redirectToRoute('app_apostador_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('apostador/edit.html.twig', ['form' => $form]);
    }
}
