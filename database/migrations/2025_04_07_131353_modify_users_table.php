<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(Schema::hasTable('users')) {
            Schema::table('users', function($table) {
                $table->dropColumn('media_sosial_type');
                $table->dropColumn('media_sosial_link');
                $table->string('jenis_kelamin')->after('alamat')->nullable();
                $table->date('tanggal_lahir')->after('jenis_kelamin')->nullable();
                $table->tinyInteger('seksi')->after('tanggal_lahir')->nullable();
                $table->string('instagram')->after('jurusan')->nullable();
                $table->string('linkedin')->after('instagram')->nullable();
                $table->date('tanggal_awal_magang')->after('linkedin')->nullable();
                $table->date('tanggal_akhir_magang')->after('tanggal_awal_magang')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasTable('users')) {
            Schema::table('users', function($table) {
                $table->string('media_sosial_type')->nullable();
                $table->string('media_sosial_link')->nullable();
                $table->dropColumn('jenis_kelamin');
                $table->dropColumn('tanggal_lahir');
                $table->dropColumn('seksi');
                $table->dropColumn('instagram');
                $table->dropColumn('linkedin');
                $table->dropColumn('tanggal_awal_magang');
                $table->dropColumn('tanggal_akhir_magang');
            });
        }
    }
};
