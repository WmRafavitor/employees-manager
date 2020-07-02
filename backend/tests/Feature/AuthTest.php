<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if when an email and password of a valid user is used, the action returns the token.
     *
     * @return void
     */
    public function testLoginIfReturnAccessTokenWheUserIsValid()
    {
        factory(\App\User::class)->create([
            'email' => 'teste@teste.com.br'
        ]);

        $response = $this->json('POST', '/api/v1/auth/login', [
            'email' => 'teste@teste.com.br',
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in'
            ]);
    }

    /**
     * Test if when an email and password of a invalid user is used, the action returns error.
     *
     * @return void
     */
    public function testLoginIfReturnErrorWheUserIsInvalid()
    {
        $response = $this->json('POST', '/api/v1/auth/login', [
            'email' => 'unknown@user.com.br',
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'error' => \Lang::get('auth.failed')
            ]);
    }
}
