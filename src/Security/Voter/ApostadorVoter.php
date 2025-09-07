<?php

/*
 *     This file is part of Bolão.
 *
 *     (c) Leonardo Rodrigues Marques <leonardo@rodriguesmarques.com.br>
 *
 *     This source file is subject to the MIT license that is bundled
 *     with this source code in the file LICENSE.
 */

namespace App\Security\Voter;

use App\Entity\Apostador;
use App\Entity\Usuario;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class ApostadorVoter extends Voter {

    public const EDIT = 'APOSTADOR_EDIT';

    protected function supports(string $attribute, mixed $subject): bool {
        return \in_array($attribute, [self::EDIT]) && $subject instanceof Apostador;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool {
        $usuario = $token->getUser();

        if (!$usuario instanceof Usuario) {
            return false;
        }

        $apostador = $subject;

        return match ($attribute) {
            self::EDIT => $this->canEdit($apostador, $usuario, $vote)
        };
    }

    private function canEdit(Apostador $apostador, Usuario $usuario, ?Vote $vote): bool {
        if ($usuario === $apostador->getUsuario()) {
            return true;
        }

        $vote?->addReason('O apostador não pertence a este usuário.');

        return false;
    }
}
