<?php

use App\Enums\MissionApprovalStatus;
use App\Enums\MissionStatus;
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
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approved_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('start_datetime');
            $table->timestamp('end_datetime');
            $table->timestamp('actual_start_datetime')->nullable();
            $table->timestamp('actual_end_datetime')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('expense_amount', 15, 2)->default(0);
            $table->enum('status',  MissionStatus::values())
                ->default(MissionStatus::PENDING->value);
            $table->enum('approval_status',  MissionApprovalStatus::values())
                ->default(MissionApprovalStatus::PENDING->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};
