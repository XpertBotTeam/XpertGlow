<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        if ($this->isMethod('post')) {
            return [
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:0',
                'sub_category_id' => 'required|integer|exists:sub_categories,id',
            ];
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'name' => 'nullable|string|max:255',
                'code' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'price' => 'nullable|numeric|min:0',
                'quantity' => 'nullable|integer|min:0',
                'sub_category_id' => 'nullable|integer|exists:sub_categories,id',
            ];
        }
    }
}
