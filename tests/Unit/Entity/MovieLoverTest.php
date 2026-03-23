<?php
// tests/Unit/Entity/MovieLoverTest.php

namespace App\Tests\Unit\Entity;
use App\Entity\MovieLover;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(MovieLover::class)]
class MovieLoverTest extends TestCase
{
    private MovieLover $user;

    protected function setUp(): void
    {
        $this->user = new MovieLover();
    }

    public function testIdIsNullByDefault(): void
    {
        $this->assertNull($this->user->getId());
    }

    public function testSettersAndGetters(): void
    {
        $createdAt = new \DateTimeImmutable('2024-01-01 10:00:00');
        $updatedAt = new \DateTimeImmutable('2024-06-15 12:00:00');

        $this->user->setEmail("john.doe@example.com");
        $this->user->setFirstname("John");
        $this->user->setLastname("Doe");
        $this->user->setPassword("hashed_password_123");
        $this->user->setCreatedAt($createdAt);
        $this->user->setUpdateAt($updatedAt);

        $this->assertSame("john.doe@example.com", $this->user->getEmail());
        $this->assertSame("John",                 $this->user->getFirstname());
        $this->assertSame("Doe",                  $this->user->getLastname());
        $this->assertSame("hashed_password_123",  $this->user->getPassword());
        $this->assertSame($createdAt,             $this->user->getCreatedAt());
        $this->assertSame($updatedAt,             $this->user->getUpdateAt());
    }

    public function testGetUserIdentifier(): void
    {
        $this->user->setEmail("john.doe@example.com");
        $this->assertSame("john.doe@example.com", $this->user->getUserIdentifier());
    }

    public function testRolesAlwaysContainRoleUser(): void
    {
        // Sans rôle défini, ROLE_USER est toujours présent
        $this->assertContains('ROLE_USER', $this->user->getRoles());
    }

    public function testSetRoles(): void
    {
        $this->user->setRoles(['ROLE_ADMIN']);
        $roles = $this->user->getRoles();

        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertContains('ROLE_USER', $roles); // toujours présent
    }

    public function testRolesAreUnique(): void
    {
        // ROLE_USER ne doit pas apparaître en double
        $this->user->setRoles(['ROLE_USER']);
        $this->assertCount(1, $this->user->getRoles());
    }

    public function testSettersReturnStatic(): void
    {
        $this->assertInstanceOf(MovieLover::class, $this->user->setEmail("test@test.com"));
        $this->assertInstanceOf(MovieLover::class, $this->user->setFirstname("Test"));
        $this->assertInstanceOf(MovieLover::class, $this->user->setLastname("User"));
        $this->assertInstanceOf(MovieLover::class, $this->user->setPassword("pass"));
        $this->assertInstanceOf(MovieLover::class, $this->user->setRoles([]));
    }
}
