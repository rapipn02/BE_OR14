<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|min:3|max:30',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|max:10',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'tolong namanya diisi',
            'name.min' => 'nama setidaknya 3 huruf',
            'name.max' => 'nama tidak boleh lebih dari 30 huruf',
            'email.required' => 'masukkan email dengan benar',
            'email.email' => 'Email is invalid',
            'email.unique' => 'email sudah terdaftar',
            'password.required' => 'masukkan pasword',
            'password.min' => 'pasword setidakny 5 karakter',
            'password.max' => 'pasword tidak boleh lebih dari 10 karakter',
        ];
    }
}
