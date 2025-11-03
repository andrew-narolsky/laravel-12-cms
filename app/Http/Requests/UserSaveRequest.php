<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|min:3|max:24|unique:users,name,' . $this->user?->id,
            'email'       => 'required|email|unique:users,email,' . $this->user?->id,
            'password'    => 'nullable|string|min:8|max:18',
            'role_id'     => 'required|integer|exists:roles,id',
        ];
    }
}
