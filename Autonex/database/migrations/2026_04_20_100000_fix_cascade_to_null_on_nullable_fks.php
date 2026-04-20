<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // sales.buyer_id: cascadeOnDelete → nullOnDelete (buyer_id is nullable)
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['buyer_id']);
            $table->foreign('buyer_id')->references('id')->on('users')->nullOnDelete();
        });

        // sales.car_id: cascadeOnDelete → nullOnDelete (car_id is nullable)
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['car_id']);
            $table->foreign('car_id')->references('id')->on('cars')->nullOnDelete();
        });

        // appointments.user_id: cascadeOnDelete → nullOnDelete (user_id is nullable)
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        // appointments.car_id: cascadeOnDelete → nullOnDelete (car_id is nullable)
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['car_id']);
            $table->foreign('car_id')->references('id')->on('cars')->nullOnDelete();
        });

        // messages.receiver_id: cascadeOnDelete → nullOnDelete (receiver_id is nullable)
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['receiver_id']);
            $table->foreign('receiver_id')->references('id')->on('users')->nullOnDelete();
        });

        // messages.car_id: cascadeOnDelete → nullOnDelete (car_id is nullable, Car uses SoftDeletes)
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['car_id']);
            $table->foreign('car_id')->references('id')->on('cars')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['buyer_id']);
            $table->foreign('buyer_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['car_id']);
            $table->foreign('car_id')->references('id')->on('cars')->cascadeOnDelete();
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['car_id']);
            $table->foreign('car_id')->references('id')->on('cars')->cascadeOnDelete();
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['receiver_id']);
            $table->foreign('receiver_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['car_id']);
            $table->foreign('car_id')->references('id')->on('cars')->cascadeOnDelete();
        });
    }
};
