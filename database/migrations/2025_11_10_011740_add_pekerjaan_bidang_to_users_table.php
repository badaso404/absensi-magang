<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'pekerjaan')) {
                $table->string('pekerjaan')->nullable()->after('email');
            }

            if (!Schema::hasColumn('users', 'bidang_suku_dinas')) {
                $table->string('bidang_suku_dinas')->nullable()->after('pekerjaan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'pekerjaan')) {
                $table->dropColumn('pekerjaan');
            }

            if (Schema::hasColumn('users', 'bidang_suku_dinas')) {
                $table->dropColumn('bidang_suku_dinas');
            }
        });
    }
};
