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
        // Add a migration comment to clarify this is for backward compatibility
        Schema::table('plants', function (Blueprint $table) {
            // Only add the column if it doesn't exist yet
            if (!Schema::hasColumn('plants', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')
                    ->comment('Added for backward compatibility with singular relationship references');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            if (Schema::hasColumn('plants', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};
