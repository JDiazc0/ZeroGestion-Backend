<?php

namespace App\Http\Requests;

use App\Enums\MeasureType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRawMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'cost' => 'sometimes|required|numeric|min:0',
            'min_quantity' => "sometimes|required|numeric|min:0",
            'measure' => "sometimes|required|string|in:" . implode(",", MeasureType::values()),
        ];
    }

    /**
     * Get custom error messages for validation
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'measure.in' => 'The selected measure is invalid. Valid options are: ' . implode(', ', MeasureType::values()),
        ];
    }
}
