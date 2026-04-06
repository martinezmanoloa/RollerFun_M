<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserGettersAndSetters(): void
    {
        // Arrange: Preparar los datos y la instancia
        $user = new User();
        $createdAt = new \DateTime('2026-04-06 12:00:00');

        // Act: Ejecutar las acciones
        $user->setName('Carlos');
        $user->setAge(30);
        $user->setCreatedAt($createdAt);

        // Assert: Verificar los resultados
        $this->assertNull($user->getId(), 'El ID debe ser nulo antes de persistir en BD');
        $this->assertEquals('Carlos', $user->getName());
        $this->assertEquals(30, $user->getAge());
        $this->assertSame($createdAt, $user->getCreatedAt());
    }
}