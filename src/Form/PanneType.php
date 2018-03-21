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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class PanneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('auteur', TextType::class)
            ->add('etat_car' , ChoiceType::class, array(
                'choices' => array(
                    'PANNE' => 'panne',
                    'ROULANT AVEC ANOMALIE' => "roulant_ano",
                    'ROULANT' => "roulant",
                    'ATELIER'   => "atelier"
                ),'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'etat_'.strtolower($key)];
                },))
            ->add('garantie', ChoiceType::class, array(
                'choices' => array(
                    'OUI' => 'oui',
                    'NON' => 'non'
                )
            ))
            ->add('date_prev', DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('date_effective',DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('nature_panne',   TextType::class, array(
                'required'  => false,
            ))
            ->add('desc_panne',     TextareaType::class)
            ->add('suites_donnes',  TextareaType::class, array(
                'required' => false,
            ))
            ->add('enregistrer',    SubmitType::class)
        ;

    }

}