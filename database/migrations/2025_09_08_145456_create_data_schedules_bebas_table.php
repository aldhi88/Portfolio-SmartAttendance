<?php

use App\Models\MasterSchedule;
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
        Schema::create('data_schedules_bebas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MasterSchedule::class)->constrained();
            $table->date('tanggal');
            $table->json('day_work')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_schedules_bebas');
    }
};
