<?php

use App\Enums\JobApplicationStatus;
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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('recommended_by')
                  ->nullable()
                  ->constrained('users')
                  ->cascadeOnDelete();
                  
            $table->foreignId('career_post_id')
                  ->nullable()
                  ->constrained('career_posts')
                  ->cascadeOnDelete();
                  
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('accepted_by')
                  ->nullable()
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('confirmed_by')
                  ->nullable()
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->boolean('is_offer_sent')->default(0);

            $table->enum('status',  JobApplicationStatus::values())
                  ->default(JobApplicationStatus::PENDING->value);
                  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
