<?php

namespace App\Form;

use App\Entity\DemandeConges;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DemandeCongesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('dateDemande',DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('typeDeConge', ChoiceType::class, array(
                'choices' => array(
                    'Repos' => 'repos',
                    'Congés payés' => 'congés payés',
                    'Exceptionnel' => 'congé exceptionnel',
                    'Congés sans solde' => 'congés sans solde',
                    'Autres' => 'Autres',
                )))
            ->add('duDateConge',DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
            ->add('auDateConge',DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'required'  => false,
                'format'    => 'dd/MM/yyyy'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DemandeConges::class,
        ]);
    }
}
