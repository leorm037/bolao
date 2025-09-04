<?php

namespace App\Controller;

use App\Repository\LoteriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/loteria', name: 'app_loteria_')]
final class LoteriaController extends AbstractController
{

    public function __construct(
            private LoteriaRepository $repository
    )
    {
        
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $loterias = $this->repository->list();

        return $this->render('loteria/index.html.twig', [
                    'loterias' => $loterias
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(): Response
    {
        return $this->render('loteria/new.html.twig');
    }
}
