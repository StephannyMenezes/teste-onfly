<?php

namespace App\Services;

use App\Enum\TravelOrderStatusEnum;
use App\Exceptions\InvalidTravelOrderStatusException;
use App\Models\TravelOrder;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TravelOrderService
{
    public function store(array $data): TravelOrder
    {
        $order = TravelOrder::create($data);

        return $order->refresh();
    }

    /**
     * @throws HttpException|InvalidTravelOrderStatusException
     */
    public function updateStatus(int $id, array $data): TravelOrder
    {
        $order = $this->show($id);

        if ($order->status === TravelOrderStatusEnum::APPROVED->value
            && $data['status'] === TravelOrderStatusEnum::CANCELED->value) {
            throw new InvalidTravelOrderStatusException();
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
     *     page?: int,
     *     per_page?: int,
     *     status?: string,
     *     from?: string,
     *     to?: string,
     *     destination?: string
     * } $data
     * @return LengthAwarePaginator
     */
    public function index(array $data): LengthAwarePaginator
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

        $page = $data['page'] ?? 1;
        $perPage = $data['per_page'] ?? 10;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function updateNotificationEmail(int $id, User $user): TravelOrder
    {
        $order = $this->show($id);
        $order->notification_email = $user->email;
        $order->save();

        return $order;
    }
}
