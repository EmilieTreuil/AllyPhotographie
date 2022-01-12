<?php

namespace App\Form;

use App\Entity\Picture;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UpdatePictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom de la photo'])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'mapped' => false,
                'class' => Category::class,
                'choice_label' => function ($category) {
                    return $category->getName();
                },
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
            'data_class' => Picture::class,
        ]);
    }
}
