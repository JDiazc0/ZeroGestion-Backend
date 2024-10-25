<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'selling_price' => 'required|numeric|min:0',
            'raw_materials' => 'required|array|min:1',
            'raw_materials.*.raw_material_id' => 'required|integer|exists:raw_materials,id',
            'raw_materials.*.quantity' => 'required|numeric|min:0',
        ];
    }
}
