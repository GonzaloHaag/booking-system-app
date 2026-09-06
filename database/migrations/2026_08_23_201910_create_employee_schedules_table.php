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
        Schema::create('employee_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('day_of_week'); /** 0 = Domingo, 1 = Lunes, ..., 6 = Sábado */
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('employee_id')->constrained(table: 'employees')->cascadeOnDelete(); /** Si se borra el empleado, se borran sus horarios */
            $table->timestamps();
            $table->unique(['employee_id', 'day_of_week'], 'unique_employee_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_schedules');
    }
};
