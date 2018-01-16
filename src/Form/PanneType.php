<?php
/**
 * Created by PhpStorm.
 * User: UTILISATEUR
 * Date: 03/01/2018
 * Time: 14:29
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class PanneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('auteur', TextType::class)
            ->add('etat_car' , ChoiceType::class, array(
                'choices' => array(
                    'PANNE' => 'panne',
                    'ROULANT AVEC ANOMALIE' => "roulant ano",
                    'ROULANT' => "roulant",
                ),'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'etat_'.strtolower($key)];
                },))
            ->add('desc_panne', TextareaType::class)
            ->add('suites_donnes', TextType::class)
            ->add('enregistrer',    SubmitType::class)
        ;

    }

}