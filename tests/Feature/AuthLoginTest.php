<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use DatabaseTransactions;
    use Request;

    public function testTryToConnectWithoutCredentials(): void
    {
        $response = $this->postJson($this->getUrl() . '/login');

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'email',
            'password'
        ]);
    }

    public function testLoginWithWrongCredentials(): void
    {
        $response = $this->postJson($this->getUrl() . '/login', [
            'email' => 'email.email@email.fr',
            'password' => 'azerty'
        ]);

        $response->assertStatus(403);
    }
}
