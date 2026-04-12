<?php

use App\Enums\InterviewStatus;
use App\Enums\InterviewType;
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
        Schema::create('job_interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('interviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('interview_type', array_map(fn($case) => $case->value, InterviewType::cases()));
            $table->timestamp('scheduled_at');

            $table->string('meeting_link')->nullable();
            $table->string('location')->nullable();

            $table->text('reschedule_reason')->nullable();

            $table->integer('technical_score')->nullable();
            $table->integer('communication_score')->nullable();
            $table->integer('attitude_score')->nullable();
            $table->integer('overall_score')->nullable();

            $table->text('hr_notes')->nullable();

            $table->enum('status', array_map(fn($case) => $case->value, InterviewStatus::cases()))->default('scheduled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_interviews');
    }
};
