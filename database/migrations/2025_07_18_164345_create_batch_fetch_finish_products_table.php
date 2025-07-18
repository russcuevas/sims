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
        Schema::create('batch_fetch_finish_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('product_id_details')->nullable();
            $table->string('product_name', 255)->nullable();
            $table->string('unit', 255)->nullable();
            $table->decimal('price', 10, 2);
            $table->string('category', 255)->nullable();
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('product_id_details')->references('id')->on('product_details')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_fetch_finish_products');
    }
};
