<?php

/*
 *     This file is part of Bolão.
 *
 *     (c) Leonardo Rodrigues Marques <leonardo@rodriguesmarques.com.br>
 *
 *     This source file is subject to the MIT license that is bundled
 *     with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\Loteria;
use App\Entity\LoteriaRateio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoteriaRateioFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        
        /** @var LoteriaRateio $loteriaRateio */
        $loteriaRateio = $options['data'];
        
        /** @var Loteria $loteria */
        $loteria = $loteriaRateio->getLoteria();
        
        $premios = $loteria->getPremios();
        
        $builder
                ->add('quantidadeDezenasJogadas', IntegerType::class, [
                    'label' => 'Quantidade de dezenas jogadas',
                    'required' => true,
                    'help' => 'Quantidade de dezenas marcadas na aposta.'
                ])
                ->add('quantidadeDezenasAcertadas', IntegerType::class, [
                    'label' => 'Quantidade de dezenas acertadas',
                    'required' => true,
                    'help' => 'Quantidade de dezenas acertadas na aposta.'
                ])
                ->add('quantidadeDezenasPremiadas', ChoiceType::class, [
                    'choices' => array_combine($premios, $premios),
                    'placeholder' => 'Selecione um prêmio',
                    'label' => 'Quantidade de dezenas premiadas',
                    'required' => true,
                    'help' => 'Quantidade de dezenas que são premiadas na loteria.'
                ])
                ->add('quantidadePremios', IntegerType::class, [
                    'label' => 'Quantidade de prêmios',
                    'required' => true,
                    'help' => 'Quantidade de prêmios que serão pagos nessa aposta.'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => LoteriaRateio::class,
        ]);
    }
}
