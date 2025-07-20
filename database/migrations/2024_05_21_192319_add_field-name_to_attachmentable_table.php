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
        Schema::table('attachmentable', function (Blueprint $table) {
            $table->string('field_name')->nullable()->after('attachmentable_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attachmentable', function (Blueprint $table) {
            $table->dropColumn('field_name');
        });
    }
};
