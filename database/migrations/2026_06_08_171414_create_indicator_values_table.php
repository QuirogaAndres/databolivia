<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indicator_values', function (Blueprint $table) {
            $table->id();

            $table->foreignId('country_id')
                ->constrained('countries')
                ->cascadeOnDelete();

            $table->foreignId('indicator_id')
                ->constrained('indicators')
                ->cascadeOnDelete();

            $table->year('year');
            $table->decimal('value', 30, 6)->nullable();

            $table->timestamps();

            $table->unique(['country_id', 'indicator_id', 'year'], 'unique_country_indicator_year');

            $table->index('year');
            $table->index(['country_id', 'indicator_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicator_values');
    }
};