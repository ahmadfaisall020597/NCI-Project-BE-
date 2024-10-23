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
        Schema::create('pelatihan', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('image_kemendikbud_ristek');
            $table->text('image_logo_nci');
            $table->text('image_logo_mitra');
            $table->string('deskripsi');
            $table->string('persyaratan');
            $table->text('image_spanduk_pelatihan');
            $table->string('duration');
            $table->string('location');
            $table->decimal('biaya', 15, 2);
            $table->string('url_daftar');
            $table->string('output');
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelatihan');
    }
};
