<?php
/**
 * Created by PhpStorm.
 * User: SIMON
 * Date: 15/03/2019
 * Time: 07:45
 */

namespace App\Service;


use App\Repository\ResultRepository;

class ScoreAndProgManager
{
    private $resultRepository;

    public function __construct(ResultRepository $resultRepository)
    {
        $this->resultRepository = $resultRepository;
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
}