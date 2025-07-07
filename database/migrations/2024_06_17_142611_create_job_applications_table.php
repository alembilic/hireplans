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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_ref')->unique()->comment('Unique reference number for the job application');
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');

            // Create 'cv' and 'cover_letter' as unsigned big integers
            $table->unsignedInteger('cv');
            $table->unsignedInteger('cover_letter')->nullable(); // Make 'cover_letter' nullable as before

            // Define foreign keys manually
            $table->foreign('cv')->references('id')->on('attachments')->onDelete('cascade');
            $table->foreign('cover_letter')->references('id')->on('attachments')->onDelete('cascade');

            $table->text('notes')->nullable(); //admin notes
            $table->timestamps();

            // Add a unique constraint on 'job_id' and 'candidate_id' combined
            $table->unique(['job_id', 'candidate_id'], 'job_candidate_unique');
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
