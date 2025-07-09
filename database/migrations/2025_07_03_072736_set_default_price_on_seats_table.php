<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set default price for new seats
        Schema::table('seats', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->default(50)->change();
        });
        // Update existing seats with price 0 or null
        DB::table('seats')->whereNull('price')->orWhere('price', 0)->update(['price' => 50]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove default (set to nullable, no default)
        Schema::table('seats', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->nullable()->default(null)->change();
        });
    }
};
