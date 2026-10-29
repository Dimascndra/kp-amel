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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('tag')->default('Literasi Keuangan');
            $table->string('tag_color')->default('blue');
            $table->string('image')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('author')->default('Tim Bank Majalengka');
            $table->string('read_time')->default('3 menit baca');
            $table->date('published_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->integer('views_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
