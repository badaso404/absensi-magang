<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Enums\Seksi;
use App\Models\User;
use App\Queries\DaftarUser;
use App\Services\AvatarStorage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    // Akses admin sudah dijaga middleware AdminCheck di routes/web.php.
    public string $mainMenu = "Admin User";

    public function __construct(private readonly AvatarStorage $avatars)
    {
    }

    public function index(Request $request): View
    {
        return $this->createView('admin.user.index', DaftarUser::untukRequest($request));
    }

    public function create(): View
    {
        return $this->createView('admin.user.create', [
            'seksiList' => Seksi::cases()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $validated['role_id'] = Role::Magang;
        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $this->avatars->store($request->file('avatar'));
        }

        User::create($validated);

        return redirect()->route('admin-user')->with('alert', [
            'type'    => 'success',
            'title'   => 'Berhasil!',
            'message' => 'User magang berhasil ditambahkan.'
        ]);
    }

    public function edit(int $id): View
    {
        return $this->createView('admin.user.edit', [
            'user'      => User::findOrFail($id),
            'seksiList' => Seksi::cases(),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate($this->rules($id));

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $this->avatars->store($request->file('avatar'), $user);
        }

        $user->update($validated);

        return redirect()->route('admin-user')->with('alert', [
            'type'    => 'success',
            'title'   => 'Berhasil!',
            'message' => 'User magang berhasil diupdate.'
        ]);
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        $this->avatars->delete($user->avatar);
        $user->delete();

        return redirect()->route('admin-user')->with('alert', [
            'type'    => 'success',
            'title'   => 'Berhasil!',
            'message' => 'User magang berhasil dihapus.'
        ]);
    }

    /**
     * Aturan validasi user magang. Saat $id diisi (update), password opsional
     * dan pengecekan unik mengabaikan baris user itu sendiri.
     */
    private function rules(?int $id = null): array
    {
        return [
            'name'                 => 'required|string|max:255',
            'email'                => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'identity_number'      => ['required', 'string', 'max:50', Rule::unique('users', 'identity_number')->ignore($id)],
            'password'             => ($id ? 'nullable' : 'required') . '|min:6|confirmed',
            'jenis_kelamin'        => 'nullable|in:Laki-laki,Perempuan',
            'tanggal_lahir'        => 'nullable|date',
            'asal'                 => 'nullable|string|max:255',
            'jurusan'              => 'nullable|string|max:255',
            'tanggal_awal_magang'  => 'nullable|date',
            'tanggal_akhir_magang' => 'nullable|date|after_or_equal:tanggal_awal_magang',
            'seksi'                => ['required', Rule::enum(Seksi::class)],
            'no_telp'              => 'nullable|string|max:20',
            'instagram'            => 'nullable|url',
            'linkedin'             => 'nullable|url',
            'avatar'               => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}