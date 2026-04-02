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
        Schema::create('buffet_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buffet_id')->constrained('buffets')->cascadeOnUpdate()->cascadeOnDelete();
            $table->json('name');
            $table->json('description');
            $table->decimal('price', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->enum('type', ['employee', 'vip'])->default('employee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buffet_items');
    }
};
