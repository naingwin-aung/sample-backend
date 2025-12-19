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
        Schema::create('booking_boats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->foreignId('booking_item_id')->nullable()->constrained('booking_items')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('option_id')->nullable()->constrained('product_options')->nullOnDelete();
            $table->foreignId('boat_id')->nullable()->constrained('boats')->nullOnDelete();
            $table->foreignId('zone_id')->nullable()->constrained('boat_zones')->nullOnDelete();
            $table->foreignId('ticket_id')->nullable()->constrained('product_tickets')->nullOnDelete();
            $table->foreignId('schedule_time_id')->nullable()->constrained('product_schedule_times')->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('ticket_name')->nullable();
            $table->date('date')->nullable();
            $table->integer('total_quantity')->nullable()->default(0);
            $table->decimal('sub_total', 10, 2)->nullable()->default(0);
            $table->decimal('grand_total', 10, 2)->nullable()->default(0);
            $table->string('payment_status')->nullable();
            $table->string('booking_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        Schema::dropIfExists('booking_boats');
    }
};
