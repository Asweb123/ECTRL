<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

class ForgotPassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',
                null,
                    [
                       'constraints' => [
                           new NotBlank(
                               [
                                   'message' => "Veuillez renseigner votre adresse email."
                               ]
                           ),
                           new NotNull(
                               [
                                   'message' => "Veuillez renseigner votre adresse email."
                               ]
                           ),
                           new Email(
                               [
                                   'message' => "L'adresse email renseignée n'est pas une adresse email valide."
                               ]
                           )
                       ]
                    ]
                )
            ->add('code',
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
