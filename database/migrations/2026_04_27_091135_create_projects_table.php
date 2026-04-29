<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_km')->nullable();
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->string('tagline_km')->nullable();
            $table->text('short_description')->nullable();
            $table->text('short_description_km')->nullable();
            $table->longText('synopsis')->nullable();
            $table->longText('synopsis_km')->nullable();
            $table->string('genre')->nullable();
            $table->string('duration')->nullable();
            $table->string('release_date')->nullable();
            $table->string('country')->default('Cambodia');
            $table->string('language')->default('Khmer');
            $table->string('status')->default('Development');
            $table->string('poster_image')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('trailer_url')->nullable();
            $table->string('youtube_id')->nullable();
            $table->string('rating')->nullable();
            $table->string('votes')->nullable();
            $table->string('year')->nullable();
            $table->string('director')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};