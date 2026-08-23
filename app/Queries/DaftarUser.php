<?php

namespace App\Queries;

use App\Enums\Role;
use App\Enums\UserSeksi;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Daftar user + statistik untuk panel admin.
 *
 * Dipakai oleh halaman Manajemen User dan halaman Absensi Admin, yang memakai
 * layout tabel yang sama persis (keduanya menampilkan daftar magang dan
 * menautkan ke rekap masing-masing).
 */
class DaftarUser
{
    /**
     * @return array<string, mixed> Data siap dilempar ke view.
     */
    public static function untukRequest(Request $request): array
    {
        $role = $request->get('role', 'all');
        $status = $request->get('status', 'active');
        $seksi = $request->get('seksi', 'all');
        $sort = $request->get('sort', 'terbaru');

        $query = self::baseQuery();

        match ($role) {
            'admin'  => $query->where('role_id', Role::Admin),
            'magang' => $query->where('role_id', Role::Magang),
            default  => null,
        };

        match ($status) {
            'active'   => $query->active(),
            'inactive' => $query->inactive(),
            default    => null,
        };

        if ($seksi !== 'all') {
            $query->where('seksi', $seksi);
        }

        match ($sort) {
            'terbaru' => $query->latest(),
            'terlama' => $query->oldest(),
            default   => $query->orderBy('name'),
        };

        return [
            'users'         => $query->paginate(10)->withQueryString(),
            'totalUsers'    => self::baseQuery()->count(),
            'activeUsers'   => self::baseQuery()->active()->count(),
            'inactiveUsers' => self::baseQuery()->inactive()->count(),
            'totalAdmin'    => self::baseQuery()->where('role_id', Role::Admin)->count(),
            'totalMagang'   => self::baseQuery()->where('role_id', Role::Magang)->count(),
            'currentStatus' => $status,
            'currentRole'   => $role,
            'currentSeksi'  => $seksi,
            'currentSort'   => $sort,
            'seksiList'     => UserSeksi::cases(),
        ];
    }

    private static function baseQuery()
    {
        return User::whereIn('role_id', [Role::Admin, Role::Magang]);
    }
}
