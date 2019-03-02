<?php

namespace App\Repository;

use App\Entity\RegisterCodes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RegisterCodes|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegisterCodes|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegisterCodes[]    findAll()
 * @method RegisterCodes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegisterCodesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RegisterCodes::class);
    }

    // /**
    //  * @return RegisterCodes[] Returns an array of RegisterCodes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RegisterCodes
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
