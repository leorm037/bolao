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

use App\Entity\Apostador;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApostadorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('nome', TextType::class, [
                    'label' => 'Nome',
                    'required' => true,
                    'attr' => [
                        'autofocus' => true,
                    ],
                ])
                ->add('email', EmailType::class, [
                    'label' => 'E-mail',
                    'required' => false,
                ])
                ->add('pix', TextType::class, [
                    'label' => 'Chave PIX',
                    'required' => false,
                ])
                ->add('telefone', TextType::class, [
                    'label' => 'Telefone',
                    'required' => false,
                ])
                ->add('celular', TextType::class, [
                    'label' => 'Celular',
                    'required' => false,
                ])
                ->add('isDefault', ChoiceType::class, [
                    'label' => 'Adicionar em todos os bolões',
                    'label_html' => true,
                    'help' => 'Adicionar este apostador automaticamente em um novo bolão.',
                    'required' => true,
                    'choices' => [
                        'Sim' => true,
                        'Não' => false,
                    ],
                    'expanded' => true,
                    'multiple' => false,
                    'label_attr' => [
                        'class' => 'radio-inline',
                    ],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Apostador::class,
        ]);
    }
}
