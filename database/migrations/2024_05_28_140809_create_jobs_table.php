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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_ref')->unique()->comment('Unique reference number for the job listing');
            $table->foreignId('employer_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique(); // For SEO-friendly URLs
            $table->text('details');
            $table->string('location'); // Could be city, state, or more detailed
            $table->string('salary')->nullable(); // Consider a salary range format
            $table->string('job_type'); // ['full-time', 'part-time', 'contract', 'internship']
            $table->string('category'); // Engineering, marketing, etc.
            $table->string('experience_level'); // Entry, mid, senior
            $table->date('application_deadline')->nullable();
            $table->boolean('is_active')->default(true); // Show/hide the job listing
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
