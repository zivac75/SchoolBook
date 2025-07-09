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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade')->comment('Service concerné');
            $table->dateTime('start_datetime')->comment('Début de la disponibilité');
            $table->dateTime('end_datetime')->comment('Fin de la disponibilité');
            $table->unsignedInteger('capacity')->comment('Capacité maximale');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['service_id', 'start_datetime']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
