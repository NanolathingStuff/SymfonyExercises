<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Img;   //images
;

class ImgButtonForm extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options): void{

        $builder
            ->add('save', ButtonType::class, [
                'attr' => ['class' => 'save',
                    'image' => 'App\Img\arrow-up.jpeg',],
            ]);
    }   
}


?>