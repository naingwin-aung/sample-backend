<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('booking_boat_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_boat_id')->nullable()->constrained('booking_boats')->nullOnDelete();
            $table->json('product')->nullable();
            $table->json('option')->nullable();
            $table->json('zone')->nullable();
            $table->json('ticket')->nullable();
            $table->json('schedule_time')->nullable();
            $table->json('variations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('booking_boat_details');
    }
};
