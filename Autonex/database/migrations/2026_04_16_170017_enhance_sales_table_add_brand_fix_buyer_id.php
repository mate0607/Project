<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Add brand column (separate from model)
            if (!Schema::hasColumn('sales', 'brand')) {
                $table->string('brand')->nullable()->after('vehicle_type');
            }

            // Make buyer_id nullable — a listing has no buyer until someone purchases
            $table->unsignedBigInteger('buyer_id')->nullable()->change();

            // Fix engine_cc: change from string to integer
            $table->unsignedInteger('engine_cc')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'brand')) {
                $table->dropColumn('brand');
            }
            $table->unsignedBigInteger('buyer_id')->nullable(false)->change();
            $table->string('engine_cc')->nullable()->change();
        });
    }
};
