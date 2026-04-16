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
        Schema::table('bukus', function (Blueprint $table) {
            // constrained() bakal otomatis nyambungin ke tabel genres
            // nullOnDelete() biar kalau genrenya dihapus, bukunnya gak ikut ilang
            $table->foreignId('genre_id')->nullable()->after('genre')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            //
        });
    }
};
