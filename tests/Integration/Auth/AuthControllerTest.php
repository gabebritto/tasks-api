<?php

namespace Tests\Integration\Auth;

use App\User\Infrastructure\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['email' => fake()->email(), 'password' => 'password']);
    }

    public function test_login_success(): void
    {
        $data = ['email' => $this->user->email, 'password' => 'password'];
        $response = $this->post('/api/auth/login', $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'Authorized',
            'status' => 200,
            'data' => [
                'token' => $response->getData(true)['data']['token']
            ]
        ]);

        $this->assertArrayHasKey('data', $response->getData(true));
        $this->assertArrayHasKey('token', $response->getData(true)['data']);
        $this->token = $response->getData(true)['data']['token'];
        $this->assertNotNull($this->token);
    }

    public function test_login_fail(): void
    {
        $data = ['email' => $this->user->email, 'password' => 'wrongpassword'];

        $response = $this->post('/api/auth/login', $data);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertArrayHasKey('message', $response->getData(true));
    }

    public function test_get_token_success(): void
    {
        $data = ['email' => $this->user->email, 'password' => 'password'];
        $response = $this->post('/api/auth/login', $data);
        $this->token = $response->getData(true)['data']['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->get('/api/auth/token');

        $response->assertStatus(Response::HTTP_OK);
        $this->assertArrayHasKey('data', $response->getData(true));
        $this->assertArrayHasKey('tokenable', $response->getData(true)['data']);
        $this->assertArrayHasKey('bearer', $response->getData(true)['data']);
    }

    public function test_get_token_fail(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer wrong_token',
        ])->get('/api/auth/token');

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function test_logout_success(): void
    {
        $data = ['email' => $this->user->email, 'password' => 'password'];
        $response = $this->post('/api/auth/login', $data);
        $this->token = $response->getData(true)['data']['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->post('/api/auth/logout');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['message' => 'Token Revoked']);
    }

    public function test_logout_fail(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer wrong_token',
        ])->post('/api/auth/logout');

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
