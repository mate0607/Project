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
            $table->dropColumn(['documents_available', 'document_type', 'technical_inspection']);
        });
    }
};
