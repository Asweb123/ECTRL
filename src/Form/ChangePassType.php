<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Uuid;

class ChangePassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uuidUser',
                null,
                [
                    'constraints' =>  [
                        new Uuid(
                            [
                                'message' => "Identifiant d'utilisateur incorrect."
                            ]
                        )
                    ]
                ])
            ->add('oldPassword',
                null,
                [
                    'constraints' => [
                        new Regex(
                            [
                                'pattern' => '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/',
                                'message' => "Votre mot de passe doit contenir au moins huit caractères dont une majuscule, une minuscule et un chiffre."
                            ]
                        ),
                        new NotBlank(
                            [
                                'message' => "Veuillez renseigner votre ancien mot de passe."
                            ]
                        )
                    ]
                ]
                )
            ->add('newPassword',
                null,
                [
                    'constraints' => [
                        new Regex(
                            [
                                'pattern' => '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/',
                                'message' => "Votre mot de passe doit contenir au moins huit caractères dont une majuscule, une minuscule et un chiffre."
                            ]
                        ),
                        new NotBlank(
                            [
                                'message' => "Veuillez renseigner votre nouveau mot de passe."
                            ]
                        )
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
