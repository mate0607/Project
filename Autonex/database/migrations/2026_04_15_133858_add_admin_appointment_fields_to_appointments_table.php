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
            $table->foreignId('user_id')->nullable()->change();
            $table->foreignId('car_id')->nullable()->change();

            $table->string('customer_name')->nullable()->after('car_id');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->string('car_brand')->nullable()->after('customer_phone');
            $table->string('car_model')->nullable()->after('car_brand');
            $table->unsignedSmallInteger('car_year')->nullable()->after('car_model');
            $table->string('car_engine')->nullable()->after('car_year');
            $table->string('car_fuel_type')->nullable()->after('car_engine');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'customer_phone',
                'car_brand',
                'car_model',
                'car_year',
                'car_engine',
                'car_fuel_type',
            ]);
        });
    }
};
