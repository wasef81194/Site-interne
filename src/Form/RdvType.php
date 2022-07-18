<?php

namespace App\Form;

use App\Entity\Rdv;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class RdvType extends AbstractType
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
                'required' => true
                ])
            ->add('adresse',null,[
                'required' => true
                ])
            ->add('cp',null,[
                'required' => true
                ])
            ->add('message',TextareaType::class,[
                'required' => true
                ])
            ->add('date',DateTimeType::class,[
                'widget' => 'single_text',
                'label'=> 'Date d\'intervention à domicile*',
                'attr'=>[
                    'class'=>'gray',
                    'placeholder'=>'Date de l\'intervention'
                ],
                'required' => true
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rdv::class,
        ]);
    }
}
