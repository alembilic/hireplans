<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update any existing employers that don't have a status set to use "In Progress" (1)
        DB::table('employers')
            ->whereNull('status')
            ->orWhere('status', 0)
            ->update(['status' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this migration as it's just setting default values
    }
};
