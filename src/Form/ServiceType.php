<?php

namespace App\Form;

use App\Entity\Service;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Veuillez saisir un titre'
                ]
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu',
                'config' => [
                    'toolbar' => 'full'
                ]
            ])
            ->add('excerpt', TextType::class, [
                'label' => 'Extrait',
                'attr' => [
                    'placeholder' => 'Saisir extrait de description'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
