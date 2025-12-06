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
        Schema::create('product_ticket_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_ticket_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('option_id')->nullable()->constrained('product_options')->nullOnDelete();
            $table->foreignId('ticket_id')->nullable()->constrained('product_tickets')->nullOnDelete();
            $table->string('name')->nullable();
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->decimal('net_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_ticket_prices');
    }
};
