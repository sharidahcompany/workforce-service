<?php

use App\Enums\ApprovalStatus;
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
        Schema::create('scholarship_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('scholarship_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('discount_percentage', 8, 2)->nullable();
            $table->string('duration')->nullable();
            $table->enum('status', array_column(ApprovalStatus::cases(), 'value'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_requests');
    }
};
