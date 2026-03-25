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
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'vehicle_type')) {
                $table->string('vehicle_type')->nullable()->after('description');
            }
            if (!Schema::hasColumn('sales', 'body_type')) {
                $table->string('body_type')->nullable()->after('vehicle_type');
            }
            if (!Schema::hasColumn('sales', 'fuel_type')) {
                $table->string('fuel_type')->nullable()->after('body_type');
            }
            if (!Schema::hasColumn('sales', 'documents_available')) {
                $table->boolean('documents_available')->default(false)->after('fuel_type');
            }
            if (!Schema::hasColumn('sales', 'document_type')) {
                $table->string('document_type')->nullable()->after('documents_available');
            }
            if (!Schema::hasColumn('sales', 'technical_inspection')) {
                $table->boolean('technical_inspection')->default(false)->after('document_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $columnsToDrop = [];

            foreach (['technical_inspection', 'document_type', 'documents_available', 'fuel_type', 'body_type', 'vehicle_type'] as $column) {
                if (Schema::hasColumn('sales', $column)) {
                    $columnsToDrop[] = $column;
                }
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
