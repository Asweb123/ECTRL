<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;

class ExpoPushTokenType extends AbstractType
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
                                    'message' => "Id d'utilisateur invalide."
                                ]
                            )
                        ]
                    ]
                )
            ->add('expoPushToken',
                    null,
                    [
                        'constraints' =>  [
                            new Type(
                                [
                                    'type' => 'string',
                                    'message' => "ExpoPushToken invalide."
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
