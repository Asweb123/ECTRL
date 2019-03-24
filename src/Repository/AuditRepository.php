<?php

namespace App\Repository;

use App\Entity\Audit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Audit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Audit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Audit[]    findAll()
 * @method Audit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuditRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Audit::class);
    }

    // /**
    //  * @return Audit[] Returns an array of Audit objects
    //  */

    public function findLastAudits($company)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.company = :val1')
            ->andWhere('a.status >= :val2')
            ->setParameters(['val1' => $company, 'val2' => 2])
            ->orderBy('a.lastModificationDate', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findLastMonthsAudits($company, $monthsNumber)
    {
        $date6MonthsAgo = new \DateTime('-'.$monthsNumber.' months');
        return $this->createQueryBuilder('a')
            ->andWhere('a.company = :val1')
            ->andWhere('a.status >= :val2')
            ->andWhere('a.lastModificationDate > :val3')
            ->setParameters(['val1' => $company, 'val2' => 2, 'val3' => $date6MonthsAgo])
            ->orderBy('a.lastModificationDate', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllAudits($company)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.company = :val1')
            ->andWhere('a.status >= :val2')
            ->setParameters(['val1' => $company, 'val2' => 2])
            ->orderBy('a.lastModificationDate', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?Audit
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
