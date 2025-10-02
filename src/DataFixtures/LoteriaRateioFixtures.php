<?php

/*
 *     This file is part of Bolão.
 *
 *     (c) Leonardo Rodrigues Marques <leonardo@rodriguesmarques.com.br>
 *
 *     This source file is subject to the MIT license that is bundled
 *     with this source code in the file LICENSE.
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoteriaRateioFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $rateios = [
            'mega-sena',
            'milionaria',
            'quina',
        ];

        $manager->flush();
    }
}
