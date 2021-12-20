<?php

namespace App\Form;

use App\Entity\Appareil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class FormAppareilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque', null , [ 'required' => true])
            ->add('modele')
            ->add('ns')
            ->add('mdp')
            ->add('prblm',TextareaType::class)
            ->add('chargeur')
            //->add('cu', null,[ 'required' => true])
            
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $appareil = $event->getData();
            $form = $event->getForm();
            if (!$appareil || null === $appareil->getId()) {
                $form->add('client',HiddenType::class);
            }
        });
    }

    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appareil::class,
        ]);
    }
}
