<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelOrderRequest;
use App\Http\Requests\UpdateTravelOrderStatusRequest;
use App\Http\Resources\TravelOrderResource;
use App\Http\Requests\IndexTravelOrderRequest;
use App\Services\TravelOrderService;

class TravelOrderController extends Controller
{
    private TravelOrderService $service;

    public function __construct(TravelOrderService $service)
    {
        $this->service = $service;
    }

    public function store(StoreTravelOrderRequest $request)
    {
        $order = $this->service->store($request->validated());

        return response()->json(TravelOrderResource::make($order), 201);
    }

    public function updateStatus($id, UpdateTravelOrderStatusRequest $request)
    {
        $order = $this->service->updateStatus($id, $request->validated());

        return response()->json(TravelOrderResource::make($order));
    }

    public function show($id)
    {
        $order = $this->service->show($id);
        return response()->json(TravelOrderResource::make($order));
    }

    public function index(IndexTravelOrderRequest $request)
    {
        $orders = $this->service->index($request->validated());

        return TravelOrderResource::collection($orders);
    }
}
