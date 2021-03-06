<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;

class UserRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('password',
                  null,
                    [
                        'constraints' => [
                            new NotBlank(
                                [
                                    'message' => "Veuillez renseigner votre code d'enregistrement."
                                ]
                            ),
                            new NotNull(
                                [
                                    'message' => "Veuillez renseigner votre code d'enregistrement."
                                ]
                            ),
                            new Type(
                                [
                                    'type' => 'string',
                                    'message' => "Le code d'enregistrement renseigné n'est pas valide."
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
            'data_class' => User::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }
}
