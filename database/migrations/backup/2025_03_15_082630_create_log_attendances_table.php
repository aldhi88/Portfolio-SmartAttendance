<?php

use App\Models\DataEmployee;
use App\Models\DataMachine;
use App\Models\MasterMinor;
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
        Schema::create('log_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DataEmployee::class)->constrained();
            $table->foreignIdFor(DataMachine::class)->constrained();
            $table->foreignIdFor(MasterMinor::class)->constrained();
            $table->dateTime('time');
            $table->timestamps();

            $table->unique(['data_machine_id', 'time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_attendances');
    }
};
