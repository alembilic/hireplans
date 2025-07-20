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
        Schema::create('reference_candidate_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reference_id')->constrained()->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');

            // Feedback categories
            $table->unsignedTinyInteger('quality_of_teaching')->nullable(); // 1: poor, 2: fair, 3: good, 4: very good, 5: outstanding
            $table->unsignedTinyInteger('breadth_of_knowledge')->nullable();
            $table->unsignedTinyInteger('relationship_with_students')->nullable();
            $table->unsignedTinyInteger('communication_with_parents')->nullable();
            $table->unsignedTinyInteger('relationship_with_colleagues')->nullable();
            $table->unsignedTinyInteger('reliability_and_integrity')->nullable();
            $table->unsignedTinyInteger('class_management')->nullable();
            $table->unsignedTinyInteger('embraces_diversity')->nullable();
            $table->unsignedTinyInteger('creativity')->nullable();
            $table->unsignedTinyInteger('time_keeping')->nullable();
            $table->unsignedTinyInteger('safe_positive_workspace')->nullable();

            // Additional questions (Disclosures)
            $table->unsignedTinyInteger('work_with_again')->nullable(); // 1: Maybe, 2: Yes, 3: No, 4: Prefer not to answer
            $table->unsignedTinyInteger('ethical_compromise')->nullable(); // 1: Maybe, 2: Yes, 3: No, 4: Prefer not to answer
            $table->unsignedTinyInteger('child_protection_issues')->nullable(); // 1: Maybe, 2: Yes, 3: No, 4: Prefer not to answer

            $table->text('comments')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reference_candidate_feedback');
    }
};

