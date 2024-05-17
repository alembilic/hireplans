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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // Laravel assumes that the name of the foreign table is the singular form of the column name, minus the _id.
            // So in this case, 'user_id' becomes 'users'.
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('candidate_ref')->unique();
            $table->string('gender')->nullable();
            $table->string('current_company')->nullable();
            $table->string('current_job_title')->nullable();
            $table->string('languages')->nullable();
            $table->string('skills')->nullable();
            $table->longText('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
