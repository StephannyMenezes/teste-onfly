<?php

namespace App\Http\Requests;

use App\Enum\TravelOrderStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
