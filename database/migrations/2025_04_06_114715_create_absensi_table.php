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
        if(!Schema::hasTable('absensi')) {
            Schema::create('absensi', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                $table->tinyInteger('status');
                $table->time('schedule_in');
                $table->time('schedule_out');
                $table->datetime('checked_in_at')->nullable();
                $table->datetime('checked_out_at')->nullable();
                $table->tinyInteger('checked_in_status');
                $table->tinyInteger('checked_out_status');
                $table->string('description')->nullable();
                $table->timestamps();
                $table->string('wfhwfo')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
