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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('service_stage')->nullable()->default(null);
            $table->string('mechanic_name')->nullable();
            $table->decimal('total_cost', 12, 2)->nullable();
            $table->text('service_report')->nullable();
            $table->text('issues_found')->nullable();
            $table->text('critical_warning')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['service_stage', 'mechanic_name', 'total_cost', 'service_report', 'issues_found', 'critical_warning']);
        });
    }
};
