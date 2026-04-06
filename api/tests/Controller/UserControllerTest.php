<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testPostUserValidationFailsWithInvalidData(): void
    {
        // Arrange: Levantar el cliente HTTP de prueba
        $client = static::createClient();
        $invalidPayload = [
            'name' => 'Al', // Inválido: menor a 3 caracteres
            'age' => 4      // Inválido: menor o igual a 5
        ];

        // Act: Realizar la petición POST
        $client->request(
            'POST',
            '/api/new/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($invalidPayload)
        );

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        // Assert: Validar los códigos HTTP y la estructura del JSON
        $this->assertEquals(400, $response->getStatusCode(), 'Debe retornar 400 por validación fallida');
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('Validation failed', $responseData['error']);
        
        // Assert: Validar que los detalles de los errores estén presentes
        $this->assertArrayHasKey('name', $responseData['details']);
        $this->assertArrayHasKey('age', $responseData['details']);
        $this->assertEquals('Name must be at least 3 characters long', $responseData['details']['name'][0]);
        $this->assertEquals('Age must be greater than 5', $responseData['details']['age'][0]);
    }
}