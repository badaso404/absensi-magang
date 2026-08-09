<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            if (!Schema::hasColumn('absensi', 'lokasi_user')) {
                $table->string('lokasi_user')->nullable()->after('wfhwfo');
            }

            if (!Schema::hasColumn('absensi', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('lokasi_user');
            }

            if (!Schema::hasColumn('absensi', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropColumn(
                array_values(array_filter(
                    ['lokasi_user', 'latitude', 'longitude'],
                    fn ($column) => Schema::hasColumn('absensi', $column)
                ))
            );
        });
    }
};
