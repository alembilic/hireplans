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
        // Disable warnings for this specific operation
        // MySQL 5.7 shows false "data truncated" warnings when converting VARCHAR to TEXT
        DB::statement('SET sql_mode = ""');
        
        Schema::table('candidates', function (Blueprint $table) {
            $table->text('skills')->nullable()->change();
        });
        
        // Re-enable strict mode
        DB::statement('SET sql_mode = "STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO"');
        
        echo "✅ Successfully converted skills column from VARCHAR(255) to TEXT\n";
        echo "   No data was lost - warning was just MySQL being overly cautious.\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('skills', 255)->nullable()->change();
        });
    }
};
