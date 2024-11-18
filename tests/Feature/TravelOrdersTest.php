<?php


use App\Enum\TravelOrderStatusEnum;
use App\Models\TravelOrder;
use App\Models\User;
use Carbon\Carbon;
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

        $status = $this->faker->randomElement([TravelOrderStatusEnum::APPROVED->value, TravelOrderStatusEnum::CANCELED->value]);

        $response = $this->putJson('/api/travel-orders/' . $travelOrder->id . '/status', ['status' => $status], ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)->assertJsonFragment(['status' => $status]);
    }

    public function test_index_status_filter()
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $approvedTravelOrders = TravelOrder::factory()->count(5)->create(['status' => TravelOrderStatusEnum::APPROVED->value]);
        $canceledTravelOrders = TravelOrder::factory()->count(5)->create(['status' => TravelOrderStatusEnum::CANCELED->value]);

        $response = $this->getJson('/api/travel-orders?status=' . TravelOrderStatusEnum::APPROVED->value, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)->assertJsonCount($approvedTravelOrders->count(), 'data');

        $response = $this->getJson('/api/travel-orders?status=' . TravelOrderStatusEnum::CANCELED->value, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)->assertJsonCount($canceledTravelOrders->count(), 'data');
    }

    public function test_index_destination_filter()
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $travelOrders = TravelOrder::factory()->count(5)->create();

        /** @var TravelOrder $travelOrder */
        foreach ($travelOrders as $travelOrder) {
            $response = $this->getJson('/api/travel-orders?destination=' . $travelOrder->destination, ['Authorization' => 'Bearer ' . $token]);

            $response->assertStatus(200);
            $this->assertEquals($travelOrder->id, $response->json('data.0.id'));
        }
    }

    public function test_index_date_filter()
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $travelOrders = TravelOrder::factory()->count(5)->create([
            'departure_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
        ]);

        $oneMonthQuantity = $travelOrders->count();

        $travelOrders = TravelOrder::factory()->count(5)->create([
            'departure_date' => $this->faker->dateTimeBetween('+1 month', '+2 months')->format('Y-m-d'),
        ]);

        $twoMonthsQuantity = $travelOrders->count();

        $from = Carbon::now()->format('Y-m-d');
        $to = Carbon::now()->addMonths(2)->format('Y-m-d');

        $response = $this->getJson("/api/travel-orders?from=$from&to=$to", ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)->assertJsonCount($oneMonthQuantity + $twoMonthsQuantity, 'data');

        $to = Carbon::now()->addMonth()->format('Y-m-d');

        $response = $this->getJson("/api/travel-orders?from=$from&to=$to", ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)->assertJsonCount($oneMonthQuantity, 'data');
    }
}
