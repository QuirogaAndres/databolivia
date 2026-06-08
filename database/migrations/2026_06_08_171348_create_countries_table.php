<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();

            $table->string('code', 10)->unique();       // Ej: BO, PE, CL
            $table->string('iso2_code', 10)->nullable(); // Ej: BO
            $table->string('name');                     // Ej: Bolivia
            $table->string('region')->nullable();        // Ej: Latin America & Caribbean
            $table->string('income_level')->nullable();  // Ej: Lower middle income
            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->index('code');
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};