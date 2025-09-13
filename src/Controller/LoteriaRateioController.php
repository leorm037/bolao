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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/loteria/{uuid:loteria}/rateio', name: 'app_loteria_rateio_', requirements: ['uuid' => '[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}'])]
final class LoteriaRateioController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Loteria $loteria): Response
    {
        $rateios = null;
        
        return $this->render('loteria_rateio/index.html.twig', [
            'loteria' => $loteria,
            'rateios' => $rateios
        ]);
    }
    
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Loteria $loteria): Response
    {
        return $this->render('loteria_rateio/index.html.twig', [
            'loteria' => $loteria
        ]);
    }
    
    #[Route('/delete', name: 'delete', methods: ['POST'])]
    public function delete(Loteria $loteria): Response
    {
        return $this->render('loteria_rateio/index.html.twig', [
            'loteria' => $loteria
        ]);
    }
}
