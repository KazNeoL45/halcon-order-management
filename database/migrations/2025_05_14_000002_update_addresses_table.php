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
        // Drop old state and country columns
        Schema::table('addresses', function (Blueprint $table) {
            if (Schema::hasColumn('addresses', 'state')) {
                $table->dropColumn('state');
            }
            if (Schema::hasColumn('addresses', 'country')) {
                $table->dropColumn('country');
            }
        });
        // Add foreign keys for state_id and country_id
        Schema::table('addresses', function (Blueprint $table) {
            $table->foreignId('state_id')->nullable()->after('colony')
                  ->constrained('states')
                  ->onDelete('cascade');
            $table->foreignId('country_id')->nullable()->after('state_id')
                  ->constrained('countries')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            // Drop foreign keys and columns
            if (Schema::hasColumn('addresses', 'country_id')) {
                $table->dropConstrainedForeignId('country_id');
            }
            if (Schema::hasColumn('addresses', 'state_id')) {
                $table->dropConstrainedForeignId('state_id');
            }
        });
        // Recreate old state and country columns
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('state')->after('colony');
            $table->string('country')->default('Mexico')->after('zip_code');
        });
    }
};