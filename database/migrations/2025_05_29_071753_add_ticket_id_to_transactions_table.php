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
    Schema::table('transactions', function (Blueprint $table) {
        if (!Schema::hasColumn('transactions', 'ticket_id')) {
            $table->unsignedBigInteger('ticket_id')->after('user_id');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
        }
    });
}

public function down()
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->dropForeign(['ticket_id']);
        $table->dropColumn('ticket_id');
    });
}
};
