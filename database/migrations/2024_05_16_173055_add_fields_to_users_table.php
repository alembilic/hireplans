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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('remember_token');
            $table->string('address_line_1')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address_line_1');
            $table->string('postcode', 10)->nullable()->after('city');
            $table->string('country', 3)->nullable()->after('postcode');
            $table->string('nationality')->nullable()->after('country');
            $table->date('dob')->nullable()->after('nationality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the fields added in the up method
            $table->dropColumn('phone');
            $table->dropColumn('address_line_1');
            $table->dropColumn('city');
            $table->dropColumn('country');
            $table->dropColumn('postcode');
            $table->dropColumn('nationality');
            $table->dropColumn('dob');
        });
    }
};
