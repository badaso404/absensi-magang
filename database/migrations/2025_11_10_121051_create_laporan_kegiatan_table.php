<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    if (!Schema::hasTable('laporan_kegiatan')) {
        Schema::create('laporan_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->text('detail_kegiatan');
            $table->string('lokasi');
            $table->string('dokumentasi')->nullable();
            $table->string('bulan');
            $table->timestamps();
        });
    }
}


    public function down(): void
    {
        Schema::dropIfExists('laporan_kegiatan');
    }
};