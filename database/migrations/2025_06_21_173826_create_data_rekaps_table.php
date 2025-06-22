<?php

use App\Models\DataEmployee;
use App\Models\LogAttendance;
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
        Schema::create('data_rekaps', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(LogAttendance::class)->constrained();
            $table->foreignIdFor(DataEmployee::class)->constrained();
            $table->dateTime('log_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_rekaps');
    }
};
