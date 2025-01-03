<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'selling_price' => 'sometimes|required|numeric|min:0',
            'raw_materials' => 'sometimes|required|array|min:1',
            'raw_materials.*.raw_material_id' => 'sometimes|required|integer|exists:raw_materials,id',
            'raw_materials.*.quantity' => 'sometimes|required|numeric|min:0',
        ];
    }
}
