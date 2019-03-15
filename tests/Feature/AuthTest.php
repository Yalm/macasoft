<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequiresEmailAndLogin()
    {
        $this->json('POST', 'api/auth/login')
            ->assertStatus(422)
            ->assertJson([
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ]);
    }
    public function testUserLoginsSuccessfully()
    {
        $user = factory(User::class)->create();

        $payload = ['email' => $user->email, 'password' => 'password'];

        $this->json('POST', 'api/auth/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'user',
                'token_type',
                'expires_in'
            ]);
    }
    public function testUserIsLoggedOutProperly()
    {
        $user = factory(User::class)->create();
        $token = auth('api')->login($user);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->json('POST', '/api/auth/logout', [])
            ->assertStatus(200);

    }
    public function testUserWithNullToken()
    {

        $user = factory(User::class)->create();
        $token = auth('api')->login($user);
        $headers = ['Authorization' => "Bearer $token"];

        $this->json('post', '/api/auth/logout', [], $headers)->assertStatus(200);

        $this->json('get', '/api/users', [], $headers)->assertStatus(401);
    }
    public function testUserWithRefreshOfToken()
    {
        $user = factory(User::class)->create();
        $token = auth('api')->login($user);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->json('POST', '/api/auth/refresh', [])
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'user',
                'token_type',
                'expires_in'
            ]);
    }

}
