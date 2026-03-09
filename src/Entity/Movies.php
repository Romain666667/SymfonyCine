<?php

namespace App\Entity;

use App\Repository\MoviesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoviesRepository::class)]
class Movies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $Date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $Time = null;

    #[ORM\Column(length: 255)]
    private ?string $Poster_path = null;

    #[ORM\Column]
    private ?int $imdb_id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Resume = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): static
    {
        $this->Title = $Title;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->Date;
    }

    public function setDate(\DateTime $Date): static
    {
        $this->Date = $Date;

        return $this;
    }

    public function getTime(): ?\DateTime
    {
        return $this->Time;
    }

    public function setTime(\DateTime $Time): static
    {
        $this->Time = $Time;

        return $this;
    }

    public function getPosterPath(): ?string
    {
        return $this->Poster_path;
    }

    public function setPosterPath(string $Poster_path): static
    {
        $this->Poster_path = $Poster_path;

        return $this;
    }

    public function getImdbId(): ?int
    {
        return $this->imdb_id;
    }

    public function setImdbId(int $imdb_id): static
    {
        $this->imdb_id = $imdb_id;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->Resume;
    }

    public function setResume(string $Resume): static
    {
        $this->Resume = $Resume;

        return $this;
    }
}
