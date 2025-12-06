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
        Schema::create('product_additional_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('option_id')->nullable()->constrained('product_options')->nullOnDelete();
            $table->foreignId('additional_option_id')->nullable()->constrained('additional_options')->nullOnDelete();
            $table->decimal('selling_price', 10, 2);
            $table->decimal('net_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_additional_options');
    }
};
