<?php

namespace App\Form;

use App\Entity\Prestation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PrestationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la prestation',
            ])
            ->add('hour',TextType::class, [
                'label' => 'Temps de la prestation',
            ])
            ->add('place', TextType::class, [
                'label' => 'Lieu de la prestation',
            ])
            ->add('picturenumber', IntegerType::class, [
                'label' => 'Nombre de photos',
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix',
            ])
            ->add('Valider', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => ['class' => 'btn btn-dark'],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prestation::class,
        ]);
    }
}
