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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade')->comment('Réservation concernée');
            $table->enum('type', ['confirmation', 'reminder', 'cancellation'])->comment('Type de notification');
            $table->timestamp('sent_at')->nullable()->comment('Date d\'envoi');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['reservation_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
