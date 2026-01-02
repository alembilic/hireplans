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
        Schema::create('quil_meetings', function (Blueprint $table) {
            $table->id();
            
            // Webhook event data
            $table->string('event_id')->unique(); // Quil webhook event ID
            $table->string('event_type')->default('meeting.completed');
            $table->bigInteger('event_created_at'); // Unix timestamp from webhook
            
            // Meeting data
            $table->string('quil_meeting_id')->unique(); // mtg_01H2J3K4L from Quil
            $table->string('meeting_name');
            $table->timestamp('start_time')->nullable();
            $table->string('owner_name')->nullable();
            $table->json('participants')->nullable(); // Array of phone numbers
            $table->string('ats_record_name')->nullable();
            $table->boolean('is_private')->default(false);
            
            // Organization data
            $table->string('account_id')->nullable();
            $table->string('team_id')->nullable();
            
            // Assets URLs
            $table->text('transcription_url')->nullable();
            $table->text('recording_url')->nullable();
            $table->text('action_items_url')->nullable();
            $table->json('database_notes')->nullable(); // Array of note objects
            $table->json('follow_up_materials')->nullable(); // Array of material objects
            
            // Relationships - optional link to scheduled meeting and candidate
            $table->foreignId('meeting_id')->nullable()->constrained('meetings')->onDelete('set null');
            $table->foreignId('candidate_id')->nullable()->constrained('candidates')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Matched by phone
            
            // Processing status
            $table->string('processing_status')->default('received'); // received, matched, unmatched
            $table->text('processing_notes')->nullable(); // For logging issues
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quil_meetings');
    }
};
