<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Uuid;

class DeleteUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uuidUser',
                null,
                    [
                        'constraints' =>  [
                            new NotBlank(
                                [
                                    'message' => "Id d'utilisateur non renseignée."
                                ]
                            ),
                            new NotNull(
                                [
                                    'message' => "Id d'utilisateur non renseignée."
                                ]
                            ),
                            new Uuid(
                                [
                                    'message' => "Identifiant d'utilisateur invalide."
                                ]
                            )
                        ]
                    ]
                )
            ->add('password',
                null,
                    [
                        'constraints' => [
                            new NotBlank(
                                [
                                    'message' => "Veuillez renseigner votre mot de passe."
                                ]
                            ),
                            new NotNull(
                                [
                                    'message' => "Veuillez renseigner votre mot de passe."
                                ]
                            ),
                            new Regex(
                                [
                                    'pattern' => '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/',
                                    'message' => "Votre mot de passe doit contenir au moins huit caractères dont une majuscule, une minuscule et un chiffre."
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
