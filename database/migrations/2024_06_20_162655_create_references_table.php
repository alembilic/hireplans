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
        Schema::create('references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('relationship');
            $table->string('position');
            $table->string('company');
            $table->string('company_address');
            $table->string('candidate_position')->nullable();
            $table->timestamp('candidate_employed_from')->nullable();
            $table->timestamp('candidate_employed_to')->nullable();
            $table->string('candidate_job_type')->nullable(); // ['full-time', 'part-time', 'contract', 'internship']
            $table->string('candidate_service_duration')->nullable();
            $table->text('candidate_leaving_reason')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('references');
    }
};
