<?php


use App\Enum\TravelOrderStatusEnum;
use App\Models\TravelOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TravelOrdersTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_protected_travel_order_routes()
    {
        $response = $this->postJson('/api/travel-orders');
        $response->assertStatus(401);

        $response = $this->getJson('/api/travel-orders/1');
        $response->assertStatus(401);

        $response = $this->putJson('/api/travel-orders/1/status');
        $response->assertStatus(401);

        $response = $this->getJson('/api/travel-orders');
        $response->assertStatus(401);
    }

    public function test_store()
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $data = [
            'requester_name' => $this->faker->name(),
            'destination' => $city = $this->faker->city(),
            'departure_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'return_date' => $this->faker->dateTimeBetween('+1 month', '+2 months')->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/travel-orders', $data, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(201)->assertJsonFragment(['destination' => $city]);
    }

    public function test_index()
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $travelOrders = TravelOrder::factory()->count(5)->create();

        $response = $this->getJson('/api/travel-orders', ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)->assertJsonCount($travelOrders->count(), 'data');
    }

    public function test_show()
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $travelOrder = TravelOrder::factory()->create();

        $response = $this->getJson('/api/travel-orders/' . $travelOrder->id, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)->assertJsonFragment(['id' => $travelOrder->id]);
    }

    public function test_update_status()
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $travelOrder = TravelOrder::factory()->create();

        $status = $this->faker->randomElement(TravelOrderStatusEnum::values());

        $response = $this->putJson('/api/travel-orders/' . $travelOrder->id . '/status', ['status' => $status], ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)->assertJsonFragment(['status' => $status]);
    }
}
