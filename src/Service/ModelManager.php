<?php

namespace App\Service;


use App\Repository\CertificationRepository;
use Doctrine\ORM\EntityManagerInterface;

class ModelManager
{
    private $em;
    private $certificationRepository;

    public function __construct(EntityManagerInterface $em,
                                CertificationRepository $certificationRepository)
    {
        $this->em = $em;
        $this->certificationRepository = $certificationRepository;
    }

    public function modelReorder($themeToDelete)
    {
        $themeRank = $themeToDelete->getRankCertification;
        $requirementsTheme = $themeToDelete->getRequirements();
        $requirementsNb = count($requirementsTheme);
/*
        $model = $this->certificationRepository->findBy([
            "certification" => $themeToDelete->getCertification(),
            "orderBy" => ["rankCertification", "ASC"]
        ]);

        foreach($model as $theme){
            if($theme->getRankCertification() > $themeRank){
                $theme->setRankCertification($theme->getRankcertification() - 1);
                if(count($theme->getRequirements()) !== 0){
                    foreach($theme->getRequirements() as $requirement){
                        $requirement->setRankCertification($requirement->getRankCertification() - $requirementsNb);
                        $this->em->persist($requirement);
                    }
                }
                $this->em->persist($theme);
            }
        }

        $this->em->remove($themeToDelete);
      //  $this->em->flush();
*/
        return $model;

    }

}