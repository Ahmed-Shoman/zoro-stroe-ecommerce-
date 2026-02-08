<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255|unique:brands,name',
            'slug'      => 'required|string|unique:brands,slug',
            'logo'      => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }
}
