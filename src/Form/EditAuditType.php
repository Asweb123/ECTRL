<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Uuid;

class EditAuditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uuidResult',
                null,
                [
                    'constraints' =>  [
                        new NotBlank(
                            [
                                'message' => "Id d'audit non renseignée."
                            ]
                        ),
                        new NotNull(
                            [
                                'message' => "Id d'audit non renseignée."
                            ]
                        ),
                        new Uuid(
                            [
                                'message' => "Id d'audit non valide."
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
            'allow_extra_fields' => true
        ]);
    }
}
