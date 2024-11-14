<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enum\TravelOrderStatusEnum;

class IndexTravelOrderRequest extends FormRequest
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
            'status' => 'string|in:' . TravelOrderStatusEnum::valuesAsString(),
            'from' => 'date|required_with:to',
            'to' => 'date|required_with:from|after_or_equal:from',
            'destination' => 'string'
        ];
    }
}
