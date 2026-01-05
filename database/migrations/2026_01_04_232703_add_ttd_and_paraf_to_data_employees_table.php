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
        Schema::table('data_employees', function (Blueprint $table) {
            $table->string('ttd')->nullable()->after('status');
            $table->string('paraf')->nullable()->after('ttd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_employees', function (Blueprint $table) {
            $table->dropColumn(['ttd', 'paraf']);
        });
    }
};
