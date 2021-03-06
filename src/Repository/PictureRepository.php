<?php

namespace App\Repository;

use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Picture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Picture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Picture[]    findAll()
 * @method Picture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Picture::class);
    }

    public function findByCategory($category)
    {
        return $this->createQueryBuilder('p')
        ->innerJoin('p.category_id', 'c', 'p.category_id = c.id_category')
        ->andWhere('c.name = :name')
        ->setParameter('name', $category)
        ->getQuery()
        ->getResult()
        ;

    }

    public function findFirstByName($name): ?Picture
    {
        return $this->createQueryBuilder('p')
        ->andWhere('p.name = :name')
        ->setParameter('name', $name)
        ->getQuery()
        ->getOneOrNullResult()
        ;

    }

    public function findOneById($id): ?Picture
    {
        return $this->createQueryBuilder('p')
        ->andWhere('p.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getOneOrNullResult()
        ;

    }

    // /**
    //  * @return Picture[] Returns an array of Picture objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Picture
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
