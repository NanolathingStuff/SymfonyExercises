<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ImgButtonForm extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options): void{
        //https://stackoverflow.com/questions/8853410/symfony-image-inside-a-button
        $builder
            ->add('execute', SubmitType::class);
    }   
}


?>