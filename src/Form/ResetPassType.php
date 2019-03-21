<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => "Les deux mots de passe renseignés doivent être identiques.",
                    'required' => true,
                    'first_options' =>
                        [
                            'label' => 'Mot de passe :',
                            'help' => 'Au moins huit caractères dont une majuscule, une minuscule et un chiffre.',
                        ],
                    'second_options' =>
                        [
                            'label' => 'Répétez le mot de passe :'
                        ],
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
                                'pattern' => '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\W]{8,}$/',
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

        ]);
    }
}
