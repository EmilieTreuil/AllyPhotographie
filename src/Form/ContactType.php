<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, [
                'label' => 'Votre Nom',
            ])
            ->add('email',EmailType::class, [
                'label' => 'Votre Email'
            ])
            ->add('objet', TextType::class, [
                'label' => 'Objet',
                'required' => false,
            ])
            ->add('message', TextareaType::class, [
                'attr' => ['rows' => 5],
                'required' => false,
                
            ])
            ->add('Valider', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => ['class' => 'btn-custom'],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
