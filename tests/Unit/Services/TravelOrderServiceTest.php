<?php

namespace Tests\Unit\Services;

use App\Enum\TravelOrderStatusEnum;
use App\Exceptions\InvalidTravelOrderStatusException;
use App\Models\TravelOrder;
use App\Services\TravelOrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TravelOrderServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_index()
    {
        $service = new TravelOrderService();

        $travelOrders = TravelOrder::factory()->count(5)->create();

        $response = $service->index([]);

        $this->assertCount($travelOrders->count(), $response);
    }

    public function test_store()
    {
        $service = new TravelOrderService();

        $data = [
            'requester_name' => $this->faker->name(),
            'destination' => $this->faker->city(),
            'departure_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'return_date' => $this->faker->dateTimeBetween('+1 month', '+2 months')->format('Y-m-d'),
        ];

        $response = $service->store($data);

        $this->assertInstanceOf(TravelOrder::class, $response);
    }

    public function test_show()
    {
        $service = new TravelOrderService();

        $travelOrder = TravelOrder::factory()->create();

        $response = $service->show($travelOrder->id);

        $this->assertEquals($travelOrder->toArray(), $response->toArray());
    }

    public function test_update_status()
    {
        $service = new TravelOrderService();

        $travelOrder = TravelOrder::factory()->create();

        $status = $this->faker->randomElement(TravelOrderStatusEnum::values());

        $response = $service->updateStatus($travelOrder->id, ['status' => $status]);

        $this->assertEquals($travelOrder->id, $response->id);
        $this->assertEquals($status, $response->status);
    }

    public function test_update_status_rules()
    {
        $service = new TravelOrderService();

        $travelOrder = TravelOrder::factory()->create(['status' => TravelOrderStatusEnum::APPROVED->value]);

        $status = TravelOrderStatusEnum::CANCELED->value;

        $this->expectException(InvalidTravelOrderStatusException::class);

        $service->updateStatus($travelOrder->id, ['status' => $status]);
    }
}
