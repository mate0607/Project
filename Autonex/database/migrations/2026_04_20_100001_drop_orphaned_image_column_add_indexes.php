<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        // Add commonly queried indexes
        Schema::table('appointments', function (Blueprint $table) {
            $table->index('status');
            $table->index('date');
            $table->index('service_stage');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->index('is_active');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->index('is_read');
        });

        Schema::table('admin_notifications', function (Blueprint $table) {
            $table->index('is_read');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('image')->nullable()->after('is_active');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['date']);
            $table->dropIndex(['service_stage']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['is_read']);
        });

        Schema::table('admin_notifications', function (Blueprint $table) {
            $table->dropIndex(['is_read']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
        });
    }
};
