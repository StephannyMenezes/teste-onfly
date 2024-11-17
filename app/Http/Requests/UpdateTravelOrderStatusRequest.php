<?php

namespace App\Http\Requests;

use App\Enum\TravelOrderStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateTravelOrderStatusRequest",
 *     type="object",
 *     @OA\Property(property="status", type="string", enum={"approved", "canceled"}, example="approved")
 * )
 */
class UpdateTravelOrderStatusRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $statuses = implode(',', [TravelOrderStatusEnum::APPROVED->value, TravelOrderStatusEnum::CANCELED->value]);

        return [
            'status' => 'required|in:' . $statuses,
        ];
    }
}
