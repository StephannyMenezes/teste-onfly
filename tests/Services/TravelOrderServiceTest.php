<?php

namespace Tests\Services;

use App\Services\TravelOrderService;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;

class TravelOrderServiceTest extends TestCase
{

    public function testIndex()
    {
        $service = new TravelOrderService();
        $response = $service->index([]);

        $this->assertInstanceOf(Collection::class, $response);
    }

    public function testStore()
    {

    }

    public function testShow()
    {

    }

    public function testUpdateStatus()
    {

    }
}
