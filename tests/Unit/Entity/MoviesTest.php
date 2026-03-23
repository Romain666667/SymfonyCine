<?php
// tests/Unit/Entity/MoviesTest.php

namespace App\Tests\Unit\Entity;

use App\Entity\Movies;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Movies::class)]
class MoviesTest extends TestCase
{
    private Movies $movie;

    protected function setUp(): void
    {
        $this->movie = new Movies();
    }

    public function testSettersAndGetters(): void
    {
        $date = new \DateTime('2024-01-15');
        $time = new \DateTime('02:30:00');

        $this->movie->setTitle("Inception");
        $this->movie->setDate($date);
        $this->movie->setTime($time);
        $this->movie->setPosterPath("/posters/inception.jpg");
        $this->movie->setImdbId(1375666);
        $this->movie->setResume("Un voleur qui s'infiltre dans les rêves.");

        $this->assertSame("Inception",                        $this->movie->getTitle());
        $this->assertSame($date,                              $this->movie->getDate());
        $this->assertSame($time,                              $this->movie->getTime());
        $this->assertSame("/posters/inception.jpg",           $this->movie->getPosterPath());
        $this->assertSame(1375666,                            $this->movie->getImdbId());
        $this->assertSame("Un voleur qui s'infiltre dans les rêves.", $this->movie->getResume());
    }

    public function testIdIsNullByDefault(): void
    {
        $this->assertNull($this->movie->getId());
    }

    public function testSettersReturnStatic(): void
    {
        // Les setters Symfony retournent static, vérifie le chaînage
        $result = $this->movie->setTitle("Test");
        $this->assertInstanceOf(Movies::class, $result);
    }
}
