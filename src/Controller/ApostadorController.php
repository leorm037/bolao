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
use App\Enum\AlertMessageEnum;
use App\Enum\TokenEnum;
use App\Form\ApostadorFormType;
use App\Repository\ApostadorRepository;
use App\Security\Voter\ApostadorVoter;
use App\Service\ApostadorService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;

#[Route('/apostador', name: 'app_apostador_')]
final class ApostadorController extends AbstractController
{

    public function __construct(
            private ApostadorRepository $repository,
            private ApostadorService $service,
    )
    {
        
    }

    #[Route('', name: 'index')]
    public function index(Request $request): Response
    {
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
                    'filter_nome' => $filter_nome_sanitized,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $apostador = new Apostador();

        $form = $this->createForm(ApostadorFormType::class, $apostador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuario = $this->getUser();

            $apostador->setUsuario($usuario);

            try {
                $this->repository->save($apostador);

                $this->addFlash(AlertMessageEnum::SUCCESS->value, \sprintf('Apostador "%s" foi cadastrado com sucesso.', $apostador->getNome()));
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash(AlertMessageEnum::DANGER->value, \sprintf('Apostador "%s" já está cadastrado.', $apostador->getNome()));
            }

            return $this->redirectToRoute('app_apostador_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('apostador/new.html.twig', ['form' => $form]);
    }

    #[Route('/{uuid:apostador}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['uuid' => '[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}'])]
    #[IsGranted('APOSTADOR_EDIT', 'apostador')]
    public function edit(Request $request, Apostador $apostador): Response
    {
        $form = $this->createForm(ApostadorFormType::class, $apostador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->repository->save($apostador);

                $this->addFlash(AlertMessageEnum::SUCCESS->value, \sprintf('Apostador "%s" foi alterado com sucesso.', $apostador->getNome()));
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash(AlertMessageEnum::DANGER->value, \sprintf('Apostador "%s" com o mesmo nome já está cadastrado.', $apostador->getNome()));
            }

            return $this->redirectToRoute('app_apostador_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('apostador/edit.html.twig', ['form' => $form]);
    }

    #[Route('/export', name: 'export', methods: ['GET'])]
    public function export(): StreamedResponse
    {
        $usuario = $this->getUser();
        $apostadores = $this->repository->list($usuario)->getIterator();

        $service = $this->service;

        $response = new StreamedResponse(function () use ($service, $apostadores) {
                    $service->exportar($apostadores);
                });

        $fileName = 'apostador-' . date('Y-m-d-H-i-s');

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
                        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                        $fileName . '.xlsx'
                ));

        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    #[Route('/delete', name: 'delete', methods: ['POST'])]
    public function delete(Request $request): Response
    {
        $token = $request->getPayload()->get('token');

        if (!$this->isCsrfTokenValid(TokenEnum::DELETE->value, $token)) {
            $this->addFlash('danger', 'Formulário de exclusão inválido, tente novamente.');

            return $this->redirectToRoute('app_apostador_index', [], Response::HTTP_SEE_OTHER);
        }

        $uuidApostador = $request->request->get('uuid');

        $uuid = Uuid::fromString($uuidApostador);

        $apostador = $this->repository->findOneByUuid($uuid);

        $this->denyAccessUnlessGranted(ApostadorVoter::DELETE, $apostador);

        if ($apostador) {
            $nome = $apostador->getNome();
            $this->repository->delete($apostador);
            $this->addFlash(AlertMessageEnum::SUCCESS->value, \sprintf('Apostador "%s" foi excluido com sucesso.', $nome));
        } else {
            $this->addFlash(AlertMessageEnum::WARNING->value, \sprintf('Apostador não encontrado.', $nome));
        }

        return $this->redirectToRoute('app_apostador_index', [], Response::HTTP_SEE_OTHER);
    }
}
