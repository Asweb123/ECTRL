<?php
/**
 * Created by PhpStorm.
 * User: SIMON
 * Date: 28/03/2019
 * Time: 11:25
 */

namespace App\Service;


use App\Entity\Certification;
use App\Entity\Requirement;
use App\Entity\Theme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;



class CsvManager
{
    const AT = "Titre modèle d'audit";
    const AD = "Description modèle d'audit";
    const TT = "Titre thèmes";
    const DT = "Description thèmes";
    const EX = "Exigences";

    private $em;
    private $themeManager;


    public function __construct(
        EntityManagerInterface $em,
        ThemeManager $themeManager)
    {
        $this->em = $em;
        $this->themeManager = $themeManager;

    }

    public function csvToDbManager($fileContent, $company)
    {

        $csvEncoder = new CsvEncoder();
        $data = $csvEncoder->decode($fileContent, []);

        if (array_key_exists(self::AT, $data[0]) === false
            || array_key_exists(self::AD, $data[0]) === false
            || array_key_exists(self::TT, $data[0]) === false
            || array_key_exists(self::DT, $data[0]) === false
            || array_key_exists(self::EX, $data[0]) === false)
        {
            return $error = "Votre fichier présente une erreur dans un ou plusieurs des entêtes présentés dans l'exemple (Entêtes obligatoires: Titre du modèle d'audit, Description du modèle d'audit, Titre du thème, Description du thème, Exigences du thème)";
        }

        //Audit check
        if ($data[0][self::AT] === "" && $data[0][self::AD] === "" && $data[0][self::TT] !== "" && $data[0][self::DT] !== "" && $data[0][self::EX] !== "") {
            return $error = "Votre fichier ne respecte pas le formatage présenté dans l'exemple. Veuillez vérifier le format de la ligne 2 de votre document.";
        } else {
            $model = new Certification();
            $model->setTitle($data[0][self::AT]);
            $model->setDescription($data[0][self::AD]);
            $model->setIsChild(true);
            $model->addCompany($company);
        }

        unset($data[0]);

        foreach ($data as $key => $row) {
            //themeCheck
            if ($row[self::AT] === "" && $row[self::AD] === "" && $row[self::TT] !== "" && $row[self::DT] !== "" && $row[self::EX] === "") {
                $theme = new Theme();
                $theme->setTitle($row[self::TT]);
                $theme->setDescription($row[self::DT]);
                $theme->setRankCertification(count($model->getThemes()) + 1);
                $theme->setCertification($model);
                $theme = $this->themeManager->colorSetter($theme, $theme->getRankCertification());
                $model->addTheme($theme);
                $themeList[] = $theme;

                $this->em->persist($theme);
                $this->em->persist($model);

            }
            //requirementCheck
            elseif ($themeList !== [] && $row[self::AT] === "" && $row[self::AD] === "" && $row[self::TT] === "" && $row[self::DT] === "" && $row[self::EX] !== "") {
                $previousTheme = end($themeList);
                $requirement = new Requirement();
                $requirement->setDescription($row[self::EX]);
                $requirement->setTheme($previousTheme);
                $requirement->setRankTheme(count($previousTheme->getRequirements()) + 1);
                $previousTheme->addRequirement($requirement);
                $requirement->setCertification($model);
                $model->addRequirement($requirement);
                $requirementList[] = $requirement;

                $this->em->persist($requirement);
                $this->em->persist($previousTheme);
                $this->em->persist($model);

            } else {
                return $error = "Votre fichier ne respecte pas le formatage présenté dans l'exemple. Veuillez vérifier le format de la ligne " . ($key + 2) . " de votre document.";
            }

        }

        if(isset($error)){
            return $error;
        }else {
            $this->em->flush();
            return $model;
        }


    }

}
