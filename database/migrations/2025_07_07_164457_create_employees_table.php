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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_firstname', 50);
            $table->string('employee_lastname', 50);
            $table->date('birthday')->nullable();
            $table->unsignedBigInteger('position_id')->nullable();
            $table->unsignedBigInteger('contract_id')->nullable();
            $table->string('email', 255)->unique()->nullable();
            $table->string('username', 50)->unique()->nullable();
            $table->string('password', 255)->nullable();
            $table->integer('pin')->nullable();
            $table->string('status', 255)->nullable();
            $table->integer('login_attempts')->default(0);
            $table->integer('is_archived')->default(0);

            $table->timestamps();
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('set null');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
