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
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique()->index();
            $table->string('nik', 16)->index();
            $table->string('nama');
            $table->text('alamat');
            $table->string('hp');
            $table->string('afiliasi')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->integer('tenor'); // bulan
            $table->string('tujuan');
            $table->string('sumber_pendapatan');
            $table->decimal('pendapatan_tetap', 15, 2);
            $table->string('pendapatan_lain')->nullable();
            $table->text('deskripsi_lain')->nullable();
            $table->text('agunan');
            $table->enum('status', ['pending', 'contacted', 'surveyed', 'approved', 'rejected'])->default('pending')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};
