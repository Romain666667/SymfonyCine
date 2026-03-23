<?php

namespace App\Repository;

use App\Entity\Movies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movies>
 */
class MoviesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movies::class);
    }

    // Requête pour un film avant cette année de sortie avec un int $year
    // Select * from movies where release_date < :date 
    public function findMoviesReleasebefore(int $year): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.Date < :date')
            ->setParameter('date', new \DateTimeImmutable($year . '-01-01'))
            ->orderBy('m.Date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function search(string $term): array
    {
    return $this->createQueryBuilder('m')
        ->where('m.Title LIKE :term')  
        ->setParameter('term', '%' . $term . '%')
        ->orderBy('m.Title', 'ASC')
        ->getQuery()
        ->getResult();
    }

    //    /**
    //     * @return Movies[] Returns an array of Movies objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Movies
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
