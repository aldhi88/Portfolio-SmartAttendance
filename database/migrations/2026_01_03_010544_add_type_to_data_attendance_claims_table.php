<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_attendance_claims', function (Blueprint $table) {
            $table->enum('type', ['Lembur', 'Normal'])
                  ->default('Normal')
                  ->after('time'); // atau after('created_by') sesuai posisi yang kamu mau
        });
    }

    public function down(): void
    {
        Schema::table('data_attendance_claims', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};

