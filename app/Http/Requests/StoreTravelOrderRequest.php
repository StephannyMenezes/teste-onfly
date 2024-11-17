<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreTravelOrderRequest",
 *     required={"requester_name", "destination", "departure_date", "return_date"},
 *     @OA\Property(property="requester_name", type="string", example="John Doe"),
 *     @OA\Property(property="destination", type="string", example="Paris"),
 *     @OA\Property(property="departure_date", type="string", format="date", example="2024-12-01"),
 *     @OA\Property(property="return_date", type="string", format="date", example="2024-12-10"),
 * )
 */
class StoreTravelOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'requester_name' => 'required|string',
            'destination' => 'required|string',
            'departure_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:departure_date',
        ];
    }
}
