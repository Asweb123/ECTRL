<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Uuid;

class NewAuditType extends AbstractType
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
                                    'message' => "Id d'utilisateur invalide."
                                ]
                            )
                        ]
                    ]
                )
            ->add('uuidCertification',
                    null,
                    [
                        'constraints' =>  [
                            new NotBlank(
                                [
                                    'message' => "Id de certification non renseignée."
                                ]
                            ),
                            new NotNull(
                                [
                                    'message' => "Id de certification non renseignée."
                                ]
                            ),
                            new Uuid(
                                [
                                    'message' => "Id de certification invalide."
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
