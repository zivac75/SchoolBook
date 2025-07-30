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
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropColumn('capacity');
            $table->string('status')->default('available')->after('end_datetime')->comment('Statut du créneau : available ou reserved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('availabilities', function (Blueprint $table) {
            $table->unsignedInteger('capacity')->comment('Capacité maximale');
            $table->dropColumn('status');
        });
    }
};
