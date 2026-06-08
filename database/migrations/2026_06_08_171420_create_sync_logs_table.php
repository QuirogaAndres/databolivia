<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('indicator_id')
                ->nullable()
                ->constrained('indicators')
                ->nullOnDelete();

            $table->string('country_code', 10)->nullable(); // Ej: BO
            $table->string('status');                       // success, error
            $table->integer('records_synced')->default(0);
            $table->text('message')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('country_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_logs');
    }
};