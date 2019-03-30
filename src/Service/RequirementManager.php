<?php
/**
 * Created by PhpStorm.
 * User: SIMON
 * Date: 28/03/2019
 * Time: 11:25
 */

namespace App\Service;


use App\Repository\RequirementRepository;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;

class RequirementManager
{
    private $em;
    private $requirementRepository;
    private $themeRepository;

    public function __construct(EntityManagerInterface $em, RequirementRepository $requirementRepository, ThemeRepository $themeRepository)
    {
        $this->em = $em;
        $this->requirementRepository = $requirementRepository;
        $this->themeRepository = $themeRepository;
    }

    public function postReturnData($requirement){

        $preparedData =  ["uuid" => $requirement->getId(), "requirement" => $requirement->getDescription()];

        return $preparedData;
    }

    public function putReturnData($requirement){

        $preparedData =  ["uuid" => $requirement->getId(), "requirement" => $requirement->getDescription()];

        return $preparedData;
    }

    public function getRequirementsManager($theme)
    {
        $requirementsNb = count($theme->getRequirements());
        $data = [];
        if($requirementsNb !== 0){

            foreach ($theme->getRequirements() as $requirement){
                $data[] = ["uuid" =>$requirement->getId(), "requirement" => $requirement->getDescription()];
            }
        }

        return $data;
    }

    public function postRequirementManager($newRequirement, $theme)
    {

        $previousRequirementRankTheme = count($theme->getRequirements());

        $newRequirement->setTheme($theme);
        $newRequirement->setCertification($theme->getCertification());
        $newRequirement->setRankTheme($previousRequirementRankTheme + 1);

        return $newRequirement;
    }

    public function deleteRequirementManager($requirement)
    {
        $theme = $requirement->getTheme();
        $themeRequirementsNb = count($theme->getRequirements());

        if ($themeRequirementsNb !== 1 || $themeRequirementsNb !== $requirement->getRankTheme()){
            $nextRequirements = $this->requirementRepository->findNextRequirements($theme, $requirement->getRankTheme());
            foreach($nextRequirements as $nextRequirement){
                $nextRequirement->setRankTheme($nextRequirement->getrankTheme() - 1);
                $this->em->persist($nextRequirement);
            }
        }
        $this->em->remove($requirement);
        $this->em->flush();

    }
}