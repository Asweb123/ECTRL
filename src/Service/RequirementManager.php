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

    //    if($previousRequirementRankTheme !== 0){
    //        $previousRequirement = $this->requirementRepository->findPreviousRequirement($previousRequirementRankTheme, $theme);
    //        $newRequirement->setRankCertification($previousRequirement->getRankCertification() + 1);
    //    }

    //    if($previousRequirementRankTheme === 0) {
    //        if ($theme->getRankCertification() > 1) {
    //            $previousTheme = $this->themeRepository->findOneBy(["rankCertification" => ($theme->getRankCertification() - 1)]);
    //            $previousRequirementRankThemeForPreviousTheme = count($previousTheme->getRequirements());
    //            $previousRequirementForPreviousTheme = $this->requirementRepository->findPreviousRequirement($previousRequirementRankThemeForPreviousTheme, $previousTheme);
    //            $newRequirement->setRankCertification($previousRequirementForPreviousTheme->getRankCertification() + 1);
    //        } else {
    //            $newRequirement->setRankCertification(1);
    //        }
    //    }

    //    $currentNextRequirements = $this->requirementRepository->findNextRequirements($model, $newRequirement->getRankCertification());
    //    if($currentNextRequirements !== null){
    //        foreach($currentNextRequirements as $requirement){
    //            $requirement->setRankCertification($requirement->getRankCertification() + 1);
    //            $this->em->persist($requirement);
    //        }
    //
    //        $this->em->flush();
    //    }

        return $newRequirement;
    }

    public function deleteRequirementManager($requirement)
    {
        $theme = $requirement->getTheme();
        $themeRequirementsNb = count($theme->getRequirements());

        $this->em->remove($requirement);

        if ($themeRequirementsNb !== 1 || $themeRequirementsNb !== $requirement->getRankTheme()){
            $nextRequirements = $this->requirementRepository->findNextRequirements($theme, $requirement->getRankTheme());
            foreach($nextRequirements as $nextRequirement){
                $nextRequirement->setRankTheme($nextRequirement->getrankTheme() - 1);
                $this->em->persist($nextRequirement);
            }
            $this->em->flush();
        }
    }
}