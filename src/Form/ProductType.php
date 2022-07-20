<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => [
                    'placeholder' => 'Nom du produit'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description du produit'
                ]
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix',
                'attr' => [
                    'placeholder' => 'Prix'
                ]
            ])
            ->add('categories', EntityType::class, [
                'label' => 'Choisir une catégorie',
                'class' => ProductCategory::class,
                'multiple' => true
            ])
            ->add('in_stock', CheckboxType::class, [
                'label' => 'En stock ?',
            ])
            ->add('is_published', CheckboxType::class, [
                'label' => 'Publier ?',
            ])
            ->add('is_promo', CheckboxType::class, [
                'label' => 'Promo ?',
            ])
            ->add('imageFilename', FileType::class, [
                'label' => 'Image de couverture',
                'mapped' => false,
                'constraints' => [
                    new Image()
                ],
            ])
            ->add('images', FileType::class, [
                'label' => 'Galerie',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
