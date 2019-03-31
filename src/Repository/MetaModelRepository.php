<?php

namespace App\Repository;

use App\Entity\MetaModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MetaModel|null find($id, $lockMode = null, $lockVersion = null)
 * @method MetaModel|null findOneBy(array $criteria, array $orderBy = null)
 * @method MetaModel[]    findAll()
 * @method MetaModel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MetaModelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MetaModel::class);
    }

    // /**
    //  * @return MetaModel[] Returns an array of MetaModel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MetaModel
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
