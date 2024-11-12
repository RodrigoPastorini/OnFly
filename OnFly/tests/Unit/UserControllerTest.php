<?php

use Tests\TestCase;
//use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;
    protected $mockOrder;

    public function testStoreCreatesANewUser()
    {
        $response = $this->postJson('user/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'User created successfully']);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);
    }

    public function testLoginReturnsTokenOnSuccess()
    {

        $user = User::factory()->create([
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('user/login', [
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function testLoginFailsWithInvalidCredentials()
    {
        $response = $this->postJson('user/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Unauthorized']);
    }
}
