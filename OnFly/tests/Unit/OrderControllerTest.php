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

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;
    protected $mockOrder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = new \App\Http\Controllers\OrderController();

        $this->mockOrder = Mockery::mock(Order::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        restore_error_handler();
        restore_exception_handler();

        parent::tearDown();
    }

    public function test_store_order_successfully()
    {
        $user = User::factory()->create();
        JWTAuth::shouldReceive('parseToken')
            ->once()
            ->andReturnSelf();
        JWTAuth::shouldReceive('authenticate')
            ->once()
            ->andReturn($user);


        $data = [
            'destination' => 'Destination A',
            'departure' => '2024-11-10 10:00:00',
            'return' => '2024-11-12 10:00:00',
        ];

        $response = $this->postJson('/orders/register', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => 'Order created successfully',
            ]);

        $this->assertDatabaseHas('orders', [
            'destination' => 'Destination A',
        ]);
    }

    public function test_store_order_unauthorized()
    {
        JWTAuth::shouldReceive('parseToken')
            ->once()
            ->andReturnSelf();
        JWTAuth::shouldReceive('authenticate')
            ->once()
            ->andThrow(new Exception("Unauthorized"));

        $data = [
            'destination' => 'Destination A',
            'departure' => '2024-11-10 10:00:00',
            'return' => '2024-11-12 10:00:00',
        ];

        $response = $this->postJson('/orders/register', $data);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Unauthorized',
            ]);
    }

    public function test_cancel_order_not_found()
    {
        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        $this->actingAs($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            ])
            ->patchJson('/orders/1/cancel')
        ;

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Order not found',
            ]);
    }

    public function test_cancel_order_successfully()
    {
        $user = User::factory()->create();

        JWTAuth::shouldReceive('parseToken')->once()->andReturnSelf();
        JWTAuth::shouldReceive('authenticate')->once()->andReturn($user);

        $order = Order::factory()->create([
            'status' => Order::STATUS_PENDING,
            'user_id' => $user->id_user,
        ]);

        $response = $this->patchJson("/orders/{$order->id}/cancel");

        $response->assertStatus(200)
            ->assertJson([
                'success' => 'Order cancelled',
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_view_specific_order()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id_user]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/orders/{$order->id}/detail");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'status' => Order::STATUS_PENDING,
                'destination' => $order->destination,
            ]);
    }

    public function test_user_cannot_cancel_order_of_another_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user2->id_user, 'status' => 'pending']);
        $token = JWTAuth::fromUser($user1);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->patchJson('/orders/' . $order->id . '/cancel');

        $response->assertStatus(401)
        ->assertJson([
            'error' => 'Unauthorized',
        ]);

        $order->refresh();
        $this->assertEquals('pending', $order->status);
    }
}
