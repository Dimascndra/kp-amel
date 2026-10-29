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
        Schema::create('publication_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category'); // e.g. TRIWULAN III - 2024, TATA KELOLA (GCG), etc.
            $table->string('category_color')->default('blue');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_size')->default('1.5 MB');
            $table->string('period')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publication_reports');
    }
};
