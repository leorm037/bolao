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

use App\Entity\Loteria;
use App\Entity\LoteriaRateio;
use App\Enum\AlertMessageEnum;
use App\Form\LoteriaRateioFormType;
use App\Repository\LoteriaRateioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/loteria/{uuid:loteria}/rateio', name: 'app_loteria_rateio_', requirements: ['uuid' => '[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}'])]
final class LoteriaRateioController extends AbstractController
{
    public function __construct(
        private LoteriaRateioRepository $repository,
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request, Loteria $loteria): Response
    {
        $registrosPorPaginas = $request->get('registros-por-pagina', 10);

        $pagina = $request->get('pagina', 1);

        $rateios = $this->repository->list(
            $loteria,
            $registrosPorPaginas,
            $pagina
        );

        return $this->render('loteria_rateio/index.html.twig', [
            'loteria' => $loteria,
            'rateios' => $rateios,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, Loteria $loteria): Response
    {
        $loteriaRateio = new LoteriaRateio();
        $loteriaRateio->setLoteria($loteria);

        $form = $this->createForm(LoteriaRateioFormType::class, $loteriaRateio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->save($loteriaRateio);

            $this->addFlash(AlertMessageEnum::SUCCESS->value, 'Rateio cadastrado com sucesso.');

            return $this->redirectToRoute('app_loteria_rateio_index', ['uuid' => $loteria->getUuid()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('loteria_rateio/new.html.twig', [
            'loteria' => $loteria,
            'form' => $form,
        ]);
    }

    #[Route('/{uuidRateio:loteriaRateio.uuid}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['uuid' => '[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}'])]
    public function edit(Request $request, Loteria $loteria, LoteriaRateio $loteriaRateio): Response
    {
        $form = $this->createForm(LoteriaRateioFormType::class, $loteriaRateio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->save($loteriaRateio);

            $this->addFlash(AlertMessageEnum::SUCCESS->value, 'Rateio alterado com sucesso.');

            return $this->redirectToRoute('app_loteria_rateio_index', ['uuid' => $loteria->getUuid()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('loteria_rateio/edit.html.twig', [
            'loteria' => $loteria,
            'form' => $form,
        ]);
    }

    #[Route('/delete', name: 'delete', methods: ['POST'])]
    public function delete(Loteria $loteria): Response
    {
        return $this->render('loteria_rateio/index.html.twig', [
            'loteria' => $loteria,
        ]);
    }
}
