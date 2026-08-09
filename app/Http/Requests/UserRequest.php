<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $avatar = Auth::user()->avatar;
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'avatar' => $avatar ? 'nullable|' : 'required|' . 'image|mimes:jpeg,png,jpg|max:2048',
            'no_telp' => 'required|numeric',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'asal' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'tanggal_awal_magang' => 'required|date',
            'tanggal_akhir_magang' => 'required|date',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid',
            'email.max' => 'Email terlalu panjang',
            'avatar.required' => 'Foto harus diupload',
            'avatar.image' => 'Format foto yang diupload harus sesuai (jpeg, png, jpg)',
            'avatar.mimes' => 'Format foto yang diupload harus sesuai (jpeg, png, jpg)',
            'avatar.max' => 'Maksimal ukuran foto yang diupload harus kurang dari 2MB',
            'no_telp.required' => 'No telp tidak boleh kosong',
            'no_telp.numeric' => 'No telp tidak valid',
            'alamat.required' => 'Alamat tidak boleh kosong',
            'jenis_kelamin.required' => 'Jenis kelamin tidak boleh kosong',
            'jenis_kelamin.max' => 'Jenis kelamin terlalu panjang',
            'tanggal_lahir.required' => 'Tanggal lahir tidak boleh kosong',
            'tanggal_lahir.date' => 'Tanggal lahir tidak valid',
            'asal.required' => 'Asal universitas / kampus tidak boleh kosong',
            'asal.max' => 'Asal universitas / kampus terlalu panjang',
            'jurusan.required' => 'Jurusan tidak boleh kosong',
            'jurusan.max' => 'Jurusan terlalu panjang',
            'instagram.required' => 'Instagram tidak boleh kosong',
            'instagram.max' => 'Instagram terlalu panjang',
            'linkedin.required' => 'LinkedIn tidak boleh kosong',
            'linkedin.max' => 'LinkedIn terlalu panjang',
            'tanggal_awal_magang.required' => 'Tanggal awal magang tidak boleh kosong',
            'tanggal_awal_magang.date' => 'Tanggal awal magang tidak valid',
            'tanggal_akhir_magang.required' => 'Tanggal akhir magang tidak boleh kosong',
            'tanggal_akhir_magang.date' => 'Tanggal akhir magang tidak valid',
        ];
    }
}
