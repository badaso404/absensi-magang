<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Menyimpan foto profil ke public/storage/images/avatar.
 *
 * Nama file selalu di-generate sendiri. Versi lama memakai
 * getClientOriginalName() apa adanya, sehingga nama file sepenuhnya
 * dikendalikan pengunggah dan bisa dipakai untuk menimpa file lain.
 */
class AvatarStorage
{
    private const DIRECTORY = 'storage/images/avatar';

    /**
     * Simpan avatar baru dan hapus yang lama. Mengembalikan nama file tersimpan.
     */
    public function store(UploadedFile $file, ?User $user = null): string
    {
        if ($user?->avatar) {
            $this->delete($user->avatar);
        }

        $filename = sprintf(
            '%s_%s.%s',
            now()->format('YmdHis'),
            Str::random(16),
            strtolower($file->extension() ?: $file->getClientOriginalExtension())
        );

        $file->move(public_path(self::DIRECTORY), $filename);

        return $filename;
    }

    public function delete(?string $filename): void
    {
        if (!$filename) {
            return;
        }

        // basename() memastikan nilai dari database tidak bisa keluar direktori.
        $path = public_path(self::DIRECTORY . '/' . basename($filename));

        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
