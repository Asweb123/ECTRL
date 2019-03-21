<?php
/**
 * Created by PhpStorm.
 * User: SIMON
 * Date: 20/03/2019
 * Time: 13:33
 */

namespace App\Service;


use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ErrorsManager
{
    public function getErrorsFromValidator(ConstraintViolationListInterface $errors)
    {
        $formattedErrors = [];
        foreach ($errors as $error) {
            $formattedErrors[] = $error->getMessage();
        }

        return $formattedErrors;
    }

    public function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[] = $childErrors;
                }
            }
        }

        return $errors;
    }
}