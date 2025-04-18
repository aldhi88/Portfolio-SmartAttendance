<?php

use App\Models\MasterLocation;
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
        Schema::create('master_machines', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MasterLocation::class)->constrained();
            $table->string('name')->unique();
            $table->string('desc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_machines');
    }
};
