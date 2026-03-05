<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('appointments', 'service') && DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE appointments MODIFY COLUMN service VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('appointments', 'service') && DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE appointments SET service = '' WHERE service IS NULL");
            DB::statement('ALTER TABLE appointments MODIFY COLUMN service VARCHAR(255) NOT NULL');
        }
    }
};
