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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('code')->index();
            $table->string('name');
            $table->string('badge')->nullable();
            $table->enum('category', ['tabungan', 'deposito', 'kredit'])->index();
            $table->string('image')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('slogan')->nullable();
            $table->text('short_desc')->nullable();
            $table->json('ketentuan')->nullable();
            $table->json('syarat')->nullable();
            $table->string('cta_text')->default('Hubungi Kami');
            $table->string('cta_type')->default('whatsapp');
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
        Schema::dropIfExists('products');
    }
};
