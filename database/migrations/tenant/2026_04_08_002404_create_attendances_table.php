<?php

use App\Enums\AttendanceStatus;
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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained('shifts')->cascadeOnDelete();

            $table->date('attendance_date');

            $table->timestamp('shift_start_at');
            $table->timestamp('shift_end_at');

            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();

            $table->integer('late_minutes')->default(0);
            $table->integer('overtime_minutes')->default(0);

            $table->enum('status', AttendanceStatus::values())
                ->default(AttendanceStatus::PENDING->value);

            $table->boolean('requires_review')->default(false);

            $table->timestamps();

            $table->unique(['user_id', 'attendance_date', 'shift_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
