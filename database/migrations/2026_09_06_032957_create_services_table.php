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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 100);
            $table->string('description', length: 255)->nullable();
            $table->decimal('price', total: 10, places: 2)->default(0);
            $table->unsignedInteger('duration_minutes')->default(30);
            $table->foreignId('tenant_id')->constrained(table: 'tenants')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
