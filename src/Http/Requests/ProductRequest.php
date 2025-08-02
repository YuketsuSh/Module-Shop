<?php

namespace Modules\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type'        => ['required', 'in:digital,physical'],
            'price'       => ['required', 'numeric', 'min:0'],
            'currency'    => ['required', 'string', 'size:3'],
            'stock'       => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'category_id' => ['nullable', 'exists:shop_categories,id'],
            'tags'        => ['nullable', 'array'],
            'tags.*'      => ['exists:shop_tags,id'],
        ];
    }

}
