<?php

namespace App\Form;

use App\Entity\DemandeAbsence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class DemandeAbsenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('dateDemande',DateType::class,  array(
                'widget'    => 'single_text',
                'html5'     => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('heureDebutAbs', TextType::class,array(
            ))
            ->add('heureFinAbs', TextType::class, array(

            ))
            ->add('motifConducteur', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DemandeAbsence::class,
        ]);
    }
}
