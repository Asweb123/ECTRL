<?php

namespace App\Repository;

use App\Entity\ExpoPushToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExpoPushToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExpoPushToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExpoPushToken[]    findAll()
 * @method ExpoPushToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpoPushTokenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExpoPushToken::class);
    }

    // /**
    //  * @return ExpoPushToken[] Returns an array of ExpoPushToken objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExpoPushToken
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
