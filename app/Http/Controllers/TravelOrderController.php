<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexTravelOrderRequest;
use App\Http\Requests\StoreTravelOrderRequest;
use App\Http\Requests\UpdateTravelOrderStatusRequest;
use App\Http\Resources\TravelOrderResource;
use App\Services\TravelOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *      name="Travel Orders",
 *      description="Endpoints relacionados a pedidos de viagem"
 *  )
 */
class TravelOrderController extends Controller
{
    private TravelOrderService $service;

    public function __construct(TravelOrderService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/travel-orders",
     *     tags={"Travel Orders"},
     *     summary="Listar todos os pedidos de viagem",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Número da página",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Quantidade de registros por página",
     *         @OA\Schema(type="integer", example=10, maximum=100),
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Status do pedido de viagem",
     *         @OA\Schema(type="string", enum={"approved", "canceled", "requested"}, example="approved")
     *     ),
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         required=false,
     *         description="Data inicial",
     *         @OA\Schema(type="string", format="date", example="2024-12-01")
     *     ),
     *      @OA\Parameter(
     *          name="to",
     *          in="query",
     *          required=false,
     *          description="Data final",
     *          @OA\Schema(type="string", format="date", example="2024-12-31")
     *      ),
     *     @OA\Parameter(
     *         name="destination",
     *         in="query",
     *         required=false,
     *         description="Destino",
     *         @OA\Schema(type="string", example="Paris")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pedidos de viagem",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TravelOrder")
     *         )
     *     )
     * )
     */
    public function index(IndexTravelOrderRequest $request): AnonymousResourceCollection
    {
        $orders = $this->service->index($request->validated());

        return TravelOrderResource::collection($orders);
    }

    /**
     * @OA\Post(
     *     path="/api/travel-orders",
     *     tags={"Travel Orders"},
     *     summary="Criar um pedido de viagem",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreTravelOrderRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pedido criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/TravelOrder")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dados inválidos fornecidos"
     *     )
     * )
     */
    public function store(StoreTravelOrderRequest $request): JsonResponse
    {
        $order = $this->service->store($request->validated());

        return response()->json(TravelOrderResource::make($order), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/travel-orders/{id}",
     *     tags={"Travel Orders"},
     *     summary="Consultar um pedido de viagem pelo ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do pedido de viagem",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do pedido de viagem",
     *         @OA\JsonContent(ref="#/components/schemas/TravelOrder")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pedido de viagem não encontrado"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $order = $this->service->show($id);
        return response()->json(TravelOrderResource::make($order));
    }

    /**
     * @OA\Put(
     *     path="/api/travel-orders/{id}",
     *     tags={"Travel Orders"},
     *     summary="Atualizar o status de um pedido de viagem",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do pedido de viagem",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTravelOrderStatusRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status do pedido de viagem atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/TravelOrder")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Não é possível cancelar um pedido aprovado"
     *     )
     * )
     */
    public function updateStatus($id, UpdateTravelOrderStatusRequest $request): JsonResponse
    {
        $order = $this->service->updateStatus($id, $request->validated());

        return response()->json(TravelOrderResource::make($order));
    }
}
