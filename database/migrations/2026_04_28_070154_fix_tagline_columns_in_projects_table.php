<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->text('tagline')->nullable()->change();
            $table->text('tagline_km')->nullable()->change();
            $table->text('short_description')->nullable()->change();
            $table->text('short_description_km')->nullable()->change();
            $table->text('trailer_url')->nullable()->change();
            $table->text('poster_image')->nullable()->change();
            $table->text('banner_image')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('tagline')->nullable()->change();
            $table->string('tagline_km')->nullable()->change();
            $table->string('short_description')->nullable()->change();
            $table->string('short_description_km')->nullable()->change();
            $table->string('trailer_url')->nullable()->change();
            $table->string('poster_image')->nullable()->change();
            $table->string('banner_image')->nullable()->change();
        });
    }
};