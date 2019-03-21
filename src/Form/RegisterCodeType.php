<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

class RegisterCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code',
                 null,
                     [
                         'constraints' => [
                            new Type(
                                [
                                    'type' => 'string',
                                    'message' => "Le code d'enregistrement renseigné n'est pas valide."
                                ]
                            ),
                            new NotBlank(
                                [
                                    'message' => "Veuillez renseigner votre code d'enregistrement."
                                ]
                            ),
                            new NotNull(
                                [
                                    'message' => "Veuillez renseigner votre code d'enregistrement."
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
            'allow_extra_fields' => true,
        ]);
    }
}
