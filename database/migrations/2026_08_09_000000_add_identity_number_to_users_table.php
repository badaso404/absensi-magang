<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'identity_number')) {
                // NISN/NIM. Nullable karena user lama belum punya nomor identitas.
                $table->string('identity_number', 50)->nullable()->unique()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'identity_number')) {
                $table->dropUnique(['identity_number']);
                $table->dropColumn('identity_number');
            }
        });
    }
};
