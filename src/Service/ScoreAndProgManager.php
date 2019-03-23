<?php
/**
 * Created by PhpStorm.
 * User: SIMON
 * Date: 15/03/2019
 * Time: 07:45
 */

namespace App\Service;


use App\Repository\AuditRepository;
use App\Repository\RequirementRepository;
use App\Repository\ResultRepository;

class ScoreAndProgManager
{
    private $resultRepository;
    private $auditRepository;
    private $requirementRepository;

    public function __construct(ResultRepository $resultRepository, AuditRepository $auditRepository, RequirementRepository $requirementRepository)
    {
        $this->resultRepository = $resultRepository;
        $this->auditRepository = $auditRepository;
        $this->requirementRepository = $requirementRepository;
    }

    public function perCentCalculator ($partial, $total)
    {
        $result = (int)(100 * $partial / $total);
        return $result;
    }

    public function scoreCalculator($requirementList, $audit)
    {
        $score = 0;
        foreach($requirementList as $requirement){
            $result = $this->resultRepository->findOneBy(['audit' => $audit, 'requirement' => $requirement]);
            if($result->getState() === 3){
                $score++;
            }
        }
        $perCentScore = $this->perCentCalculator($score, count($requirementList));

        return $perCentScore;
    }

    public function averageLastAuditsScore($lastAudits)
    {
        $totalScore = 0;
        foreach ($lastAudits as $audit){
            $totalScore = $totalScore + $audit->getScore();
        }

        $averageLastAuditsScore = $totalScore/count($lastAudits);

        return $averageLastAuditsScore;
    }

    public function recurrentRequirementsFailed($company)
    {

        $last6MonthsAudits = $this->auditRepository->findLast6MonthsAudits($company);

        $resultList = [];
        foreach ($last6MonthsAudits as $audit){
            foreach ($audit->getResults() as $result){
                if ($result->getState() === 1){
                    $resultList[] = $result;
                }
            }
        }

        $requirementList = [];
        foreach($resultList as $result){
            if(array_key_exists($result->getRequirement()->getId(), $requirementList) === false){
                $requirementList[$result->getRequirement()->getId()] = 1;
            } else {
                $requirementList[$result->getRequirement()->getId()]++;
            }
        }
        $recurrentRequirement= array_slice($requirementList, 0, 4);

        $recurrentRequirementList = [];
        foreach ($recurrentRequirement as $key => $recurrence){
            $requirement = $this->requirementRepository->find($key);
            $recurrentRequirementList[] = [
                "description" => $requirement->getDescription(),
                "certification" => $requirement->getCertification(),
                "recurrence" => $recurrence
            ];
        }

        return $recurrentRequirementList;
    }
}