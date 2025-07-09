<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('seat_id')->nullable()->after('id');
            $table->unsignedBigInteger('event_id')->nullable()->after('seat_id');
            $table->unsignedBigInteger('user_id')->nullable()->after('event_id');
            $table->decimal('price', 8, 2)->nullable()->after('user_id');

            // Foreign keys (optional)
            $table->foreign('seat_id')->references('id')->on('seats')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['seat_id']);
            $table->dropForeign(['event_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['seat_id', 'event_id', 'user_id', 'price']);
        });
    }
};