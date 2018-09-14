<?php

namespace App\Form;

use App\Entity\QuestionsPaie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class QuestionsPaieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('dateCreation')
            ->add('dateDemande',DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'required'  => true,
                'format'    => 'mm/yyyy',
                'attr'      => array(
                    'class'             => 'col-md-12 form-control',
                    'autocomplete'      => 'off'
                ),
                'label_attr'=> array(
                    'class'   => 'control-label '
                )

            ))
            ->add('objetDemande', TextType::class)
            //->add('statueDemande')
            //->add('statueDemandeDirection')
            //->add('reponseDirection')
            //->add('questionsPaieConducteur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuestionsPaie::class,
        ]);
    }
}
