<?php

namespace App\Form;

use App\Entity\RapportHebdo;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RapportHebdoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateReclame',DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'required'  => true,
                'format'    => 'dd/MM/yyyy',
                'attr'      => array(
                    'class' => 'col-md-3 form-control jsdatepicker'
                ),
                'label_attr'=> array(
                  'class'   => 'control-label'
                )

            ))
            ->add('travailHorsTachy', ChoiceType::class, array(
                'choices' => array(
                    'TRAVAIL ATELIER' => "travail atelier",
                    'TRAVAIL BRUEAU' => "travail bureau",
                    'CONDUITE VL'   => "conduite vl",
                    'RELAIS (Avion, Train)' => "relais (avion, train)",
                    "AUTRE TRAVAIL"         => 'autre travail'
                ),
                'attr'      => array(
                    'class' => 'col-md-3 form-control'
                )
                ))
            ->add('heureRapport', ChoiceType::class, array(
                'choices' => array(
                    '00' => '00',
                    '01' => '01',
                    '02' => '02',
                    '03' => '03',
                    '04' => '04',
                    '05' => '05',
                    '06' => '06',
                    '07' => '07',
                    '08' => '08',
                    '09' => '09',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                    '13' => '13',
                    '14' => '14',
                    '15' => '15',
                    '16' => '16',
                    '17' => '17',
                    '18' => '18',
                    '19' => '19',
                    '20' => '20',
                    '21' => '21',
                    '22' => '22',
                    '23' => '23',
                ),
                'attr' => array(
                    'class' => 'form-control col-md-3'
                )
            ))
            ->add('minRapport', ChoiceType::class, array(
                'choices' => array(
                    '00' => '00',
                    '05' => '05',
                    '10' => '10',
                    '15' => '15',
                    '20' => '20',
                    '25' => '25',
                    '30' => '30',
                    '35' => '35',
                    '40' => '40',
                    '45' => '45',
                    '50' => '50',
                    '55' => '55',
                ),
                'attr' => array(
                    'class' => 'form-control col-md-3'
                )
            ))
            ->add('heureFinRapport', ChoiceType::class, array(
                'choices' => array(
                    '00' => '00',
                    '01' => '01',
                    '02' => '02',
                    '03' => '03',
                    '04' => '04',
                    '05' => '05',
                    '06' => '06',
                    '07' => '07',
                    '08' => '08',
                    '09' => '09',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                    '13' => '13',
                    '14' => '14',
                    '15' => '15',
                    '16' => '16',
                    '17' => '17',
                    '18' => '18',
                    '19' => '19',
                    '20' => '20',
                    '21' => '21',
                    '22' => '22',
                    '23' => '23',
                ),
                'attr' => array(
                    'class' => 'form-control col-md-3'
                )
            ))
            ->add('minFinRapport', ChoiceType::class, array(
                'choices' => array(
                    '00' => '00',
                    '05' => '05',
                    '10' => '10',
                    '15' => '15',
                    '20' => '20',
                    '25' => '25',
                    '30' => '30',
                    '35' => '35',
                    '40' => '40',
                    '45' => '45',
                    '50' => '50',
                    '55' => '55',
                ),
                'attr' => array(
                    'class' => 'form-control col-md-3'
                )
            ))
            ->add('repasMidi', ChoiceType::class, array(
                'choices' => array(
                    'OUI' => 1,
                    'NON' => 0,
                ),
                'attr' => array(
                    'class' => 'form-control col-md-3'
                )
                ))
            ->add('repasSoir', ChoiceType::class, array(
                'choices' => array(
                    'OUI' => 1,
                    'NON' => 0,
                ),
                'attr' => array(
                    'class' => 'form-control col-md-3'
                )
            ))
            ->add('observationsRapport', TextareaType::class, array(
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RapportHebdo::class,
        ]);
    }
}
