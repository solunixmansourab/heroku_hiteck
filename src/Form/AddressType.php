<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label'=> 'Prénom',
                'attr'=> [
                    'placeholder' => 'Entrer le prénom du destinataire'
                ],
            ])
            ->add('lastName', TextType::class, [
                'label'=> 'Nom',
                'attr'=> [
                    'placeholder' => 'Entrer le nom du destinataire'
                ],
            ])
            ->add('email', EmailType::class, [
                'label'=> 'Adresse email',
                'attr'=> [
                    'placeholder' => "Entrer l'email du destinataire"
                ],
            ])
            ->add('phone', TextType::class, [
                'label'=> 'Téléphone',
                'attr'=> [
                    'placeholder' => 'Entrer le numero de téléphone du destinataire'
                ],
            ])
            ->add('adresse', TextType::class, [
                'label'=> 'Adresse',
                'attr'=> [
                    'placeholder' => "Entrer l'adresse de livraison du colis"
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
