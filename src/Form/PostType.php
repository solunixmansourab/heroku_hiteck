<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\PostCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de l'article",
                'attr' => [
                    'placeholder' => "Titre de l'article",
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => "Contenu",
                'attr' => [
                    'placeholder' => "Taper du contenu",
                ]
            ])
            ->add('is_published', CheckboxType::class, [
                'label' => 'Publier',
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => PostCategory::class,
                'attr' => [
                    'placeholder' => 'Choisir une catégorie',
                ]
            ])
            ->add('postImageFile', FileType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image(),
                ],
                'attr' => [
                    'placeholder' => 'Uploader une image',
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
