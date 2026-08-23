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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 100);
            $table->date('birthdate')->nullable();
            $table->string('phone', length: 20)->nullable();
            $table->foreignId('user_id')->nullable()->unique()->constrained(table: 'users')->nullOnDelete();
            $table->foreignId('tenant_id')->constrained(table: 'tenants')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
