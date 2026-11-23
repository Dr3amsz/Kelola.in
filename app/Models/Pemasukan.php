<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pemasukan harian toko sembako.
     */
    public function up(): void
    {
        Schema::create('pemasukan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->index()->comment('Tanggal pemasukan dicatat');
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete()
                ->comment('Pemilik toko yang mencatat pemasukan');
            $table->string('keterangan', 255)->comment('Sumber atau deskripsi pemasukan');
            $table->decimal('nominal', 15, 2)->comment('Jumlah pemasukan dalam rupiah');
            $table->text('catatan')->nullable()->comment('Catatan tambahan opsional');
            $table->timestamps();
        });
    }

    /**
     * Hapus tabel pemasukan jika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukan');
    }
};