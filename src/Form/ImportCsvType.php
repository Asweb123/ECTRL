<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ImportCsvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("file", FileType::class, [
                'constraints' => [
                    new NotBlank(),
                    new File(
                        [
                            'maxSize' => '5M',
                            'maxSizeMessage' => 'Le fichier ne doit pas dépasser 5MB',
                            'mimeTypes' => ['text/comma-separated-values', 'text/x-comma-separated-values', 'text/plain', 'text/csv', 'text/x-csv', 'text/anytext', 'application/csv', 'application/excel', 'application/vnd.msexce', 'application/vnd.ms-excel'],
                            'mimeTypesMessage' => 'Le fichier envoyé n\'est pas de type csv (format reçu: {{ type }}).'
                        ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}