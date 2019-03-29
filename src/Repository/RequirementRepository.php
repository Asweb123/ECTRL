<?php

namespace App\Repository;

use App\Entity\Requirement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Requirement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Requirement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Requirement[]    findAll()
 * @method Requirement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequirementRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Requirement::class);
    }

    // /**
    //  * @return Requirement[] Returns an array of Requirement objects
    //  */

    public function findNextRequirements($theme, $rankTheme)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.theme = :val1')
            ->andWhere('r.rankTheme > :val2')
            ->setParameters(['val1' => $theme, 'val2' => $rankTheme])
            ->orderBy('r.rankTheme', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

}
