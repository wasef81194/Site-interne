<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
date_default_timezone_set('Europe/Paris');
class FormClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('date',HiddenType::class)
            ->add('personne', ChoiceType::class, [
                'choices'  => [
                    'M' => 'M',
                    'Mme' => 'Mme',
                    'Autre' => 'Autre',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('nom', null,[ 
                'required' => true
                ])
            ->add('prenom', null,[
                'required' => true
                ])
            ->add('mail', EmailType::class,[
                'required' => true
                ])
            ->add('tel', null,[
                'required' => true
                ])
            ->add('rue', null,[
                 'required' => true
                 ])
            ->add('ville', null,[
                'required' => true
                ])
            ->add('cp', null,[
                'required' => true
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
