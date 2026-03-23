<?php

namespace App\Repository;

use App\Entity\Rating;
use App\Entity\Movies;
use App\Entity\MovieLover;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    // Tous les ratings d'un film
    public function findByMovie(Movies $movie): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.movie = :movie')
            ->setParameter('movie', $movie)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // Moyenne des notes d'un film
    public function findAverageScoreByMovie(Movies $movie): ?float
    {
        return $this->createQueryBuilder('r')
            ->select('AVG(r.score)')
            ->andWhere('r.movie = :movie')
            ->setParameter('movie', $movie)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Tous les ratings d'un MovieLover
    public function findByMovieLover(MovieLover $movieLover): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.movieLover = :movieLover')
            ->setParameter('movieLover', $movieLover)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // Rating d'un MovieLover pour un film précis
    public function findOneByMovieAndMovieLover(Movies $movie, MovieLover $movieLover): ?Rating
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.movie = :movie')
            ->andWhere('r.movieLover = :movieLover')
            ->setParameter('movie', $movie)
            ->setParameter('movieLover', $movieLover)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // Top N films les mieux notés
    public function findTopRated(int $limit = 10): array
    {
        return $this->createQueryBuilder('r')
            ->select('AVG(r.score) as avgScore, IDENTITY(r.movie) as movieId')
            ->groupBy('r.movie')
            ->orderBy('avgScore', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
