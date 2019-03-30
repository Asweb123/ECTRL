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
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class CsvManager
{
    private $em;
    private $requirementRepository;
    private $themeRepository;
    private $serializer;

    public function __construct(EntityManagerInterface $em,
                                RequirementRepository $requirementRepository,
                                ThemeRepository $themeRepository,
                                SerializerInterface $serializer
                               )
    {
        $this->em = $em;
        $this->requirementRepository = $requirementRepository;
        $this->themeRepository = $themeRepository;
        $this->serializer = $serializer;
    }

    public function csvToDbManager($file){
        $data[] = $this->serializer->deserialize($file, '','csv');
        dump($data);
    }
}