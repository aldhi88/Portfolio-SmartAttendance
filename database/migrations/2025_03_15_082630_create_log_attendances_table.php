<?php

use App\Models\DataEmployee;
use App\Models\MasterMachine;
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
            $table->foreignIdFor(DataEmployee::class);
            $table->foreignIdFor(MasterMachine::class);
            $table->foreignIdFor(MasterMinor::class);
            $table->string('name');
            $table->dateTime('time');
            $table->unsignedBigInteger('edited_by')->nullable();
            $table->enum('source_by', ['sync', 'edit'])->default('sync');
            $table->timestamps();
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
