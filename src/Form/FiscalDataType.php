<?php

namespace App\Form;

use App\Entity\FiscalData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class FiscalDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('surname')    //<input type="text" id="surname" name="surname" size="50" maxlength="40">
            ->add('name')   //<input type="text" id="name" name="name" size="50" maxlength="40">
            ->add('gender', ChoiceType::class, [
                'choices'  => [
                    'Male' => False,
                    'Female' => True,
                ],]) //<select name="formGender">  
            ->add('born_place') //<input type="text" id="born_place" name="born_place" size="40">
            ->add('province')   //<input type="text" id="province" name="province" size="2" maxlength="2">
            ->add('birth_day', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,])  //DATA DI NASCITA:    <!-- php make user choose a date 
            ->add('submit', SubmitType::class, [
                'label' => 'Calculate Code',
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FiscalData::class,
        ]);
    }
}
