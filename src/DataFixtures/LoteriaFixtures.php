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

use App\Entity\Loteria;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoteriaFixtures extends Fixture
{

    public function load(ObjectManager $manager): void {
        $loterias = [
            [
                'nome' => 'Mega-Sena',
                'urlSlug' => 'mega-sena',
                'urlApi' => 'https://servicebus2.caixa.gov.br/portaldeloterias/api/megasena',
                'apostas' => range(6, 15, 1),
                'dezenas' => range(1, 60, 1),
                'premios' => [6, 5, 4]
            ],
            [
                'nome' => '+Milionária',
                'urlSlug' => 'milionaria',
                'urlApi' => 'https://servicebus2.caixa.gov.br/portaldeloterias/api/maismilionaria',
                'apostas' => range(6, 12, 1),
                'dezenas' => range(1, 50, 1),
                'premios' => [6, 5, 4]
            ],
            [
                'nome' => 'Quina',
                'urlSlug' => 'quina',
                'urlApi' => 'https://servicebus2.caixa.gov.br/portaldeloterias/api/quina',
                'apostas' => range(5, 15, 1),
                'dezenas' => range(1, 80, 1),
                'premios' => [6, 5, 4]
            ],
        ];

        foreach ($loterias as $item) {
            $loteria = new Loteria();

            $loteria
                    ->setNome($item['nome'])
                    ->setUrlSlug($item['urlSlug'])
                    ->setUrlApi($item['urlApi'])
                    ->setApostas($item['apostas'])
                    ->setDezenas($item['dezenas'])
                    ->setPremios($item['premios'])
            ;

            $manager->persist($loteria);
        }

        $manager->flush();
    }
}
