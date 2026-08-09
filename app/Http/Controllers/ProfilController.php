<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest;
use App\Models\User;
use App\Services\AvatarStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public string $mainMenu = 'Profil';

    public function __construct(private readonly AvatarStorage $avatars)
    {
    }

    public function index(): View
    {
        return $this->createView('profil.index');
    }

    public function editProfil(): View
    {
        return $this->createView('profil.edit-profil');
    }

    public function updateProfil(Request $request): JsonResponse
    {
        $user = User::findOrFail(Auth::id());

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'asal'          => 'required|string|max:255',
            'jurusan'       => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat'        => 'required|string',
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $this->avatars->store($request->file('avatar'), $user);
        }

        $user->update($validated);

        session()->flash('alert', [
            'type'    => 'success',
            'title'   => 'Berhasil',
            'message' => 'Profil telah diperbaharui!',
        ]);

        return response()->json(['redirect' => route('profil')]);
    }

    public function updateProfilPassword(PasswordRequest $request): JsonResponse
    {
        $user = User::findOrFail(Auth::id());

        // Password hanya disimpan sebagai hash. Versi lama juga menyimpan
        // encrypt($password) ke kolom remember_temp — itu reversibel, jadi
        // siapa pun yang punya APP_KEY bisa membaca password asli semua user.
        $user->password = Hash::make($request->validated()['password']);
        $user->save();

        session()->flash('alert', [
            'type'    => 'success',
            'title'   => 'Berhasil',
            'message' => 'Password telah diperbaharui!',
        ]);

        return response()->json(['redirect' => route('profil')]);
    }
}
