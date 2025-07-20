<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained()->cascadeOnDelete(); // Link to Employer
            $table->string('title');
            $table->string('slug')->unique(); // For SEO-friendly URLs
            $table->text('description');
            $table->string('location'); // Could be city, state, or more detailed
            $table->enum('type', ['full-time', 'part-time', 'contract', 'internship']);
            $table->string('salary')->nullable(); // Consider a salary range format
            $table->date('application_deadline')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
