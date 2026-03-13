<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('appointments', 'description')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->text('description')->nullable()->after('time');
            });
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending','confirmed','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending','confirmed','completed') NOT NULL DEFAULT 'pending'");
        }

        if (Schema::hasColumn('appointments', 'description')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
};
