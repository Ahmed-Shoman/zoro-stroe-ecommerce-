<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'slug'      => 'required|string|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
            'icon'      => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }
}
