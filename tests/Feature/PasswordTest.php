<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_generate_password(): void
    {
        $response = $this->postJson('/api/passwords', [
            'length'    => 12,
            'uppercase' => true,
            'lowercase' => true,
            'numbers'   => true,
            'symbols'   => false,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'password' => [
                         'id',
                         'password',
                         'length',
                         'uppercase',
                         'lowercase',
                         'numbers',
                         'symbols',
                     ]
                 ]);

        // Confirma que a senha tem o tamanho correto
        $this->assertEquals(12, strlen($response->json('password.password')));
    }

    public function test_cannot_generate_password_without_length(): void
    {
        $response = $this->postJson('/api/passwords', [
            'uppercase' => true,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['length']);
    }

    public function test_can_list_passwords(): void
    {
        // Cria uma senha primeiro
        $this->postJson('/api/passwords', [
            'length'    => 8,
            'uppercase' => true,
            'lowercase' => true,
            'numbers'   => false,
            'symbols'   => false,
        ]);

        $response = $this->getJson('/api/passwords');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'passwords' => [
                         '*' => ['id', 'password', 'length']
                     ]
                 ]);
    }
}
