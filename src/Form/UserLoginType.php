<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;

class UserLoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',
                null,
                    [
                       'constraints' => [
                           new Email(
                               [
                                   'message' => "L'adresse email renseignée n'est pas une adresse email valide."
                               ]
                           )
                       ]
                    ]
                )
            ->add('password',
                 null,
                     [
                        'constraints' => [
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
