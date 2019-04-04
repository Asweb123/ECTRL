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

    public function modelCreationLeft($company){
        $currentModelList = count($company->getCertifications());
        $maxCertificationsNb = $company->getMaxCertificationsNb();

        if($maxCertificationsNb === null){
            $modelCreationLeft = null;
        } elseif ($currentModelList > $maxCertificationsNb) {
            $modelCreationLeft = 0;
        } else {
            $modelCreationLeft = $maxCertificationsNb - $currentModelList;
        }

        return $modelCreationLeft;
    }
}