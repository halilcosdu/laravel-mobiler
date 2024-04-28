<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TokenRequest extends FormRequest
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
        return [
            'timezone' => ['required'],
            'os_type' => ['required', 'in:android,ios,other'],
            'os_version' => ['required'],
            'device_name' => ['required'],
            'device_type' => ['required'],
            'app_version' => ['required'],
            'client_device_code' => ['required'],
            'language_code' => ['required'],
            'country_code' => ['required'],
        ];
    }
}
