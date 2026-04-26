<?php

use App\Enums\CareerStatus;
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
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->json('name');
            $table->json('description');
            $table->enum('status', CareerStatus::values())
                  ->default(CareerStatus::ACTIVE->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};
