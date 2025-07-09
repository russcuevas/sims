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
        Schema::create('history_finish_products', function (Blueprint $table) {
            $table->id();
            $table->string('transact_id', 255)->nullable();
            $table->string('product_name', 255)->nullable();

            $table->integer('quantity')->nullable();
            $table->string('unit', 255)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->string('process_by', 255)->nullable();
            $table->date('process_date')->nullable();
            $table->integer('is_archived')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_finish_products');
    }
};
