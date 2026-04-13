<?php

use App\Enums\MissionApprovalStatus;
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
        Schema::create('attendance_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('deduct_from_balance')->default(false);
            $table->text('reason')->nullable();
            $table->timestamp('start_datetime');
            $table->timestamp('end_datetime');
            $table->enum('status',  MissionApprovalStatus::values())
                ->default(MissionApprovalStatus::PENDING->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_permissions');
    }
};
