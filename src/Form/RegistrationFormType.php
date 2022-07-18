<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'label' => 'Prénom *',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Tous les champs sont obligatoires'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre prénom devrait comporter au moins {{ limit }} characteres',
                    ]),
                ],
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Nom *',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Tous les champs sont obligatoires'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre nom devrait comporter au moins {{ limit }} characteres',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email *',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Tous les champs sont obligatoires'
                    ])
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'required' => 'false',
                'label' => 'Mot de passe *',
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Tous les champs sont obligatoires',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
