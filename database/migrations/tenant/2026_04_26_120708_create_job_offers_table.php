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
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('career_id')
                  ->constrained('careers')
                  ->cascadeOnDelete();

            $table->foreignId('job_application_id')
                  ->nullable()  
                  ->constrained('job_applications')
                  ->cascadeOnDelete();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->decimal('salary',10,2); 
            $table->enum('status',  ApprovalStatus::values())
                  ->default(ApprovalStatus::PENDING->value);     
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
