<?php

namespace App\Form;

use App\Entity\Square;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class SquareFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('width', IntegerType::class, array(
                'label' => 'Width',
                'data' => 600   //default value
           ))
            ->add('height', IntegerType::class, array(
                'label' => 'Height',
                'data' => 600
           ))
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Square::class,
        ]);
    }
}