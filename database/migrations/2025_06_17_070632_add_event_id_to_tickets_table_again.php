<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    if (!Schema::hasColumn('tickets', 'event_id')) {
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->nullable()->after('seat_id');
        });
    }
}

public function down()
{
    if (Schema::hasColumn('tickets', 'event_id')) {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('event_id');
        });
    }
}
};