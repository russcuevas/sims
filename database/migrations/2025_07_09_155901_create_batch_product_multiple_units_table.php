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
        Schema::create('batch_product_multiple_units', function (Blueprint $table) {
            $table->id();
            // UPDATED
            $table->string('identity_no', 255)->nullable();
            
            // UPDATED
            $table->string('product_name', 255);
            $table->string('quantity', 255)->nullable();
            $table->string('stock_unit_id', 255);
            $table->decimal('product_price', 10, 2);
            $table->integer('is_selected')->default(0)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_product_multiple_units');
    }
};
