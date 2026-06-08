<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();

            $table->foreignId('indicator_category_id')
                ->constrained('indicator_categories')
                ->cascadeOnDelete();

            $table->string('code')->unique();       // Ej: SP.POP.TOTL
            $table->string('name');                 // Ej: Población total
            $table->text('description')->nullable();
            $table->string('unit')->nullable();      // Ej: USD, %, personas
            $table->boolean('active')->default(true);
            $table->boolean('featured')->default(false);

            $table->timestamps();

            $table->index('code');
            $table->index('active');
            $table->index('featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicators');
    }
};