<?php

namespace App\Form;

use App\Entity\Editeur;
use App\Entity\User;
use App\Entity\Etat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class FormEditeur extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date',HiddenType::class)
            
            ->add('user', EntityType::class,[
                'class' => User::class,
                'choice_label' => 'login',
            ])
            ->add('etat', EntityType::class,[
                'class' => Etat::class,
                'choice_label' => 'statut',
            ])
            ->add('mail', CheckboxType::class, [
                'mapped'=>false,
                'required'=>false,
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
            ]);
            ;
           // ->add('etat')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Editeur::class,
        ]);
    }
}
