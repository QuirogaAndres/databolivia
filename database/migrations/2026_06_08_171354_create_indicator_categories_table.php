<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indicator_categories', function (Blueprint $table) {
            $table->id();

            $table->string('name');             // Ej: Economía
            $table->string('slug')->unique();   // Ej: economia
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->index('slug');
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicator_categories');
    }
};