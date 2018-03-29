<?php
/**
 * Created by PhpStorm.
 * User: UTILISATEUR
 * Date: 27/12/2017
 * Time: 20:47
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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('immat', TextType::class)
            ->add('site', ChoiceType::class, array(
                'choices' => array(
                    'La Roche sur Foron' => "la Roche sur Foron",
                    'Viry' => "Viry",
                    'Rumilly' => "Rumilly",
                    'Cluses' => "Cluses",
                ),
            ))
            ->add('num_serie', TextType::class)
            ->add('auteur', TextType::class)
            ->add('marque', ChoiceType::class, array(
                'choices' => array(
                    'IVECO CROSSWAY' => "iveco crossway",
                    'IVECO MAGELYS' => "iveco magelys",
                    'MERCEDES TOURISMO' => 'mercedes tourismo',
                    'IVECO DAILY' => "iveco daily",
                    'VOLVO 9700HD' => "volvo 9700 hd",
                    'SCANIA TOURING' => "scania touring",
                    'IVECO RECREO' => "iveco recreo",
                    'SOLARIS' => "solaris",
                    'BOVA' => "bova",
                    'IRIZAR I4' => "irizar i4",
                ),))
            ->add('nb_places', NumberType::class, array(
                'required' => false
            ))
            ->add('euro', TextType::class, array(
                'required' => false
            ))
            ->add('locataire',  ChoiceType::class, array(
                'choices' => array(
                    'Aps' => "APS",
                    'Fle' => "FLE",
                ),
                'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'attending_'.strtolower($key)];
                },))
            ->add('date_prev', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'format'    => 'dd/MM/yyyy'
                ))
            ->add('condition_garantie', TextType::class, array(
                'required' => false
            ))
            ->add('date_mar', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
                ))
            ->add('ct', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('date_extincteur', DateType::class, array(
                'widget' => 'single_text',
                'required'  => false,
                'format'    => 'd/M/y'
            ))
            ->add('date_limiteur', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('date_tachy', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('date_ethylo', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('fin_garantie', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('etat_car', ChoiceType::class, array(
                'choices' => array(
                    'ROULANT' => "roulant",
                    'ROULANT AVEC ANOMALIE' => "roulant ano",
                    'PANNE' => 'panne',
                ),'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'etat_'.strtolower($key)];
                },))
            ->add('garantie', ChoiceType::class,array(
                'choices' =>array(
                    'OUI'   => 'oui',
                    'NON'   => 'non'
                )
            ))
            ->add('proprio_pneus', ChoiceType::class, array(
                'choices' => array(
                    'Aps' => "APS",
                    'Michelin' => "MICHELIN",
                    'Firstop' => "FIRSTOP",
                ),
                'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'attending_'.strtolower($key)];
                },))
            ->add('memo_car', TextareaType::class, array(
                'required' => false,
            ))

            // Equipements
            ->add('siege_guide', CheckboxType::class, array(
                'label'     => 'Siège guide',
                'required'  => false,
            ))
            ->add('wc', CheckboxType::class, array(
                'required' => false
            ))
            ->add('ufr', CheckboxType::class, array(
                'required' => false
            ))
            ->add('usb', CheckboxType::class, array(
                'required'=> false
            ))
            ->add('prises_elec', CheckboxType::class, array(

                'required' => false
            ))
            ->add('porte_ski', CheckboxType::class, array(
                'required' => false
            ))

            ->add('enregistrer',    SubmitType::class)
        ;

    }

}