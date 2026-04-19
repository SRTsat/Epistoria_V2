<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            // Ubah status jadi string agar bisa nampung 'rusak' atau 'hilang'
            $table->string('status')->change(); 
            // Pastikan denda defaultnya 0
            $table->integer('denda')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
