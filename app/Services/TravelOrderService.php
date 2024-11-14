<?php

namespace App\Services;

use App\Models\TravelOrder;
use App\Enum\TravelOrderStatusEnum;
use Illuminate\Support\Collection;

class TravelOrderService
{
    public function store(array $data): TravelOrder
    {
        $order = TravelOrder::create($data);

        return $order->refresh();
    }

    public function updateStatus(int $id, array $data): TravelOrder
    {
        $order = $this->show($id);

        if ($order->status === TravelOrderStatusEnum::APPROVED->value
            && $data['status'] === TravelOrderStatusEnum::CANCELED->value) {
            throw new \Exception('Cannot cancel an approved order');
        }

        $order->status = $data['status'];
        $order->save();

        return $order;
    }

    public function show($id): TravelOrder
    {
        return TravelOrder::findOrFail($id);
    }

    /**
     * @param array{
     *     status?: string,
     *     from?: string,
     *     to?: string,
     *     destination?: string
     * } $data
     * @return Collection
     */
    public function index(array $data): Collection
    {
        $query = TravelOrder::query();

        if (isset($data['status'])) {
            $query->where('status', $data['status']);
        }
        if (isset($data['from']) && isset($data['to'])) {
            $query->whereBetween('departure_date', [$data['from'], $data['to']]);
        }
        if (isset($data['destination'])) {
            $query->where('destination', $data['destination']);
        }

        return $query->get();
    }
}
