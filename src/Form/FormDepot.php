<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\FormClientType;
use App\Form\FormAppareilType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class FormDepot extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client',FormClientType::class, [ 
                'label'=> false,
            ])
            ->add('appareil', FormAppareilType::class,[ 
                'label'=> false
            ])
            ->add('cu', CheckboxType::class,[ 
                'required' => true, 
                'mapped'=>false, 
                'label'=>'Acceptez les conditions de prise en charge'
            ]);
            ;
            
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => 'register'
        ]);
    }
}
