<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CommandForm extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options): void{
        // https://symfony.com/doc/current/form/multiple_buttons.html 
        $builder
            ->add('goUp', SubmitType::class)
            ->add('goLeft', SubmitType::class)
            ->add('goDown', SubmitType::class)
            ->add('goRight', SubmitType::class)
            ->add('ball', SubmitType::class)
            ->add('bowling_ball', SubmitType::class)
            ->add('silver_ball', SubmitType::class);
    }   
    /*the options "src", "value" do not exist. Defined options are: "attr", "attr_translation_parameters", 
    "auto_initialize", "block_name", "block_prefix", "disabled", "label", "label_format", "label_html", 
    "label_translation_parameters", "priority", "row_attr", "translation_domain".*/
}


?>