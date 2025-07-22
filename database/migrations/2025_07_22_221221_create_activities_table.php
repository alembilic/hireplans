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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->string('activity_type'); // profile_created, job_applied, application_status_changed, etc.
            $table->string('title'); // Short title for the activity
            $table->text('description')->nullable(); // Longer description
            $table->json('metadata')->nullable(); // Additional data (job_id, application_id, old_status, new_status, etc.)
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // User who created the activity (null for system events)
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['candidate_id', 'created_at']);
            $table->index('activity_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
