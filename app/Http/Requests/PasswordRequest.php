<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            // min:6 disamakan dengan form tambah/ubah user di panel admin.
            'current_password' => 'required|string|current_password',
            'password' => 'required|string|min:6|different:current_password|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'current_password.required' => 'Password lama tidak boleh kosong',
            'current_password.current_password' => 'Password lama tidak sesuai',
            'password.different' => 'Password tidak boleh sama dengan sebelumnya',
            'password.confirmed' => 'Password konfirmasi tidak sesuai',
            'password_confirmation.required' => 'Password konfirmasi tidak boleh kosong',
            'password_confirmation.confirmed' => 'Password konfirmasi tidak sesuai',
        ];
    }
}
