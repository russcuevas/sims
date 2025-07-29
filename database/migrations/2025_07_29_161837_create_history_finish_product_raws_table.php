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
        Schema::create('history_finish_product_raws', function (Blueprint $table) {
            $table->id();
            $table->string('transact_id', 255)->nullable();
            $table->string('product_name')->nullable();
            $table->string('current_quantity')->nullable();
            $table->string('quantity', 255)->nullable();
            $table->string('unit', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_finish_product_raws');
    }
};
