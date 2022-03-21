<?php

namespace App\Form;

use App\Entity\Appel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AppelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null,[
                'required' => true
                ])
            ->add('prenom',null,[
                'required' => true
                ])
            ->add('tel',null,[
                'required' => true
                ])
            ->add('mail',EmailType::class,[
                'required' => false
                ])
            ->add('objet',null,[
                'required' => true
                ])
            ->add('message',TextareaType::class,[
                'required' => true
                ])
            ->add('completed',CheckboxType::class,[
                'mapped'=> false
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appel::class,
        ]);
    }
}
