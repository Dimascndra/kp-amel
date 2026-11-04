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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code', 32)->unique()->index();
            $table->string('nama', 150);
            $table->string('email', 150)->index();
            $table->string('telepon', 30);
            $table->string('nomor_rekening', 50)->nullable();
            $table->string('kategori', 50)->default('layanan');
            $table->string('judul', 200);
            $table->text('isi_pengaduan');
            $table->string('lampiran', 255)->nullable();
            $table->string('status', 30)->default('diproses')->index(); // diproses, ditindaklanjuti, selesai, ditolak
            $table->text('catatan_petugas')->nullable();
            $table->string('ditangani_oleh', 150)->nullable();
            $table->timestamp('tanggal_ditindaklanjuti')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();

            // Anti-Fraud & Security Tracking
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('fraud_risk_level', 20)->default('low'); // low, medium, high
            $table->text('fraud_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
