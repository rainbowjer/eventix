<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dateTime('reminder_7days_sent_at')->nullable()->after('resell_admin_note');
            $table->dateTime('reminder_1hour_sent_at')->nullable()->after('reminder_7days_sent_at');
            $table->dateTime('reminder_started_sent_at')->nullable()->after('reminder_1hour_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['reminder_7days_sent_at', 'reminder_1hour_sent_at', 'reminder_started_sent_at']);
        });
    }
}; 