<?php

namespace App\Http\Resources;

use App\Models\TravelOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TravelOrder",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="requester_name", type="string", example="John Doe"),
 *     @OA\Property(property="destination", type="string", example="Paris"),
 *     @OA\Property(property="departure_date", type="string", format="date", example="2024-12-01"),
 *     @OA\Property(property="return_date", type="string", format="date", example="2024-12-10"),
 *     @OA\Property(property="status", type="string", example="solicitado"),
 *     @OA\Property(property="created_at", type="string", format="date", example="2024-12-01"),
 *     @OA\Property(property="updated_at", type="string", format="date", example="2024-12-01"),
 * )
 *
 * @mixin TravelOrder
 */
class TravelOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'requester_name' => $this->requester_name,
            'destination' => $this->destination,
            'departure_date' => $this->departure_date->format('Y-m-d'),
            'return_date' => $this->return_date->format('Y-m-d'),
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
