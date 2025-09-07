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

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController {

    public function __construct(
            private TranslatorInterface $translator
    ) {
        
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error) {
            $message = "Credenciais inválidas.";
            
            $this->addFlash('danger', $message);
        }

        return $this->render('security/login.html.twig', [
                    'last_username' => $lastUsername
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
