<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                Rule::unique('settings', 'name')->ignore($this->setting),
            ],
            'slug' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('settings', 'slug')->ignore($this->setting),
            ],
            'value' => [
                'required',
                'string',
            ],
        ];
    }
}
