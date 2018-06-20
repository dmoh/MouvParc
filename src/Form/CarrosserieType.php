<?php

namespace App\Form;

use App\Entity\Carrosserie;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarrosserieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('auteur',             TextType::class)
            ->add('nature_accro', TextType::class)
            ->add('etat_car',           ChoiceType::class, array(
                'choices' => array(
                    'IMMOBILISATION' => 'immobilisation',
                    'Gênant' => "genant",
                    'Sans incidence' => "sans incidence",
                    'RAS'   => "ras"
                ),'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'etat_'.strtolower($key)];
                },))
            ->add('desc_accro',         TextareaType::class)
            ->add('suite_donnee',       TextareaType::class)
            ->add('date_signalement', DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('date_remise_etat', DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'format'    => 'dd/MM/yyyy',
                'required'  => false
            ))
            ->add('envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Carrosserie::class,
        ]);
    }
}
