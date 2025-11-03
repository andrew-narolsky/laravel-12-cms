<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search'  => 'nullable|string|max:50',
            'role_id' => 'nullable|integer|exists:roles,id'
        ];
    }
}
