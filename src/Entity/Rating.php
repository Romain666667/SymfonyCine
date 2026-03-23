<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $score = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne(targetEntity: Movies::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Movies $movie = null;

    #[ORM\ManyToOne(targetEntity: MovieLover::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?MovieLover $movieLover = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        if ($score < 1 || $score > 5) {
            throw new \InvalidArgumentException('Le score doit être entre 1 et 5.');
        }
        $this->score = $score;
        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;
        return $this;
    }

    public function getMovie(): ?Movies
    {
        return $this->movie;
    }

    public function setMovie(?Movies $movie): static
    {
        $this->movie = $movie;
        return $this;
    }

    public function getMovieLover(): ?MovieLover
    {
        return $this->movieLover;
    }

    public function setMovieLover(?MovieLover $movieLover): static
    {
        $this->movieLover = $movieLover;
        return $this;
    }
}
