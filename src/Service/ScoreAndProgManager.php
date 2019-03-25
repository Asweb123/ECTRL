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

        $last6MonthsAudits = $this->auditRepository->findLastMonthsAudits($company, 6);

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
        arsort($requirementList);
        $recurrentRequirement= array_slice($requirementList, 0, 5);

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

    public function auditsPerScore($company)
    {
        $last12MonthsAudits = $this->auditRepository->findLastMonthsAudits($company,12);

        $auditPerScore = [0, 0, 0, 0, 0];
        foreach($last12MonthsAudits as $audit){
            if($audit->getScore() >= 95){
                $auditPerScore[0]++;
            }
            if($audit->getScore() >= 85 && $audit->getScore() < 95){
                $auditPerScore[1]++;
            }
            if($audit->getScore() >= 75 && $audit->getScore() < 85){
                $auditPerScore[2]++;
            }
            if($audit->getScore() >= 65 && $audit->getScore() < 75){
                $auditPerScore[3]++;
            }
            if($audit->getScore() < 65){
                $auditPerScore[4]++;
            }
        }

        return $auditPerScore;
    }

    public function auditsScorePerType($company)
    {
        $allAudits = $this->auditRepository->findAllAudits($company, 'ASC');

        $auditsScorePerType = [];
        foreach($company->getCertifications() as $certification){
                $auditsScorePerType[$certification->getTitle()] = ["name" => $certification->getTitle(), "data" => []];
        }

        foreach($allAudits as $audit){
            $date = $audit->getLastModificationDate();
            $timestampDate = $date->getTimestamp() * 1000;

            $scorePerDateAudit = [$timestampDate, $audit->getScore()];

            foreach( $auditsScorePerType as $certification){
                if(in_array($audit->getCertification()->getTitle(), $certification)){
                    array_push($certification["data"], $scorePerDateAudit);
                }
                $auditsScorePerType[$audit->getCertification()->getTitle()] = $certification;
            }
        }
/*
        foreach($auditsScorePerType as $key => $certification){
            if(empty($certification["data"])){
                unset($auditsScorePerType[$key]);
            }
        }
*/

        return $auditsScorePerType;
    }

}