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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['video', 'phone']);
            $table->dateTime('scheduled_at');
            $table->integer('duration_minutes');
            $table->text('description')->nullable();
            $table->foreignId('job_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('meeting_link')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('google_event_id')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
