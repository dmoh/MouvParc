<?php

namespace App\Form;

use App\Entity\DemandesConducteurs;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandesConducteursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_demande',DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('datej1',DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('datej2',DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('datej3',DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('datej4',DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('datej5',DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('datej6',DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('travailHorsTachy', TextType::class)
                ->add('repasMidi', ChoiceType::class, array(
                    'choices' => array(
                        'OUI' => 1,
                        'NON' => 0,
                    )))
            ->add('repasSoir', ChoiceType::class, array(
                'choices' => array(
                    'OUI' => 1,
                    'NON' => 0,
                )))
            ->add('observationsConducteur', TextareaType::class, array(
                'required' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DemandesConducteurs::class,
        ]);
    }
}
