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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('tenant_id')->constrained(table: 'tenants')->restrictOnDelete();
            $table->foreignId('employee_id')->constrained(table: 'employees')->restrictOnDelete();
            $table->foreignId('customer_id')->constrained(table: 'customers')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['employee_id', 'date', 'start_time', 'end_time'], 'unique_appointment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
