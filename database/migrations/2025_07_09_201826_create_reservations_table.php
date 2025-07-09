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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Utilisateur ayant réservé');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade')->comment('Service réservé');
            $table->foreignId('availability_id')->constrained('availabilities')->onDelete('cascade')->comment('Créneau réservé');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending')->comment('Statut de la réservation');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'service_id', 'availability_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
