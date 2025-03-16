<?php

use App\Models\MasterFunction;
use App\Models\MasterLocation;
use App\Models\MasterOrganization;
use App\Models\MasterPosition;
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
        Schema::create('data_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MasterOrganization::class)->constrained();
            $table->foreignIdFor(MasterPosition::class)->constrained();
            $table->foreignIdFor(MasterLocation::class)->constrained();
            $table->foreignIdFor(MasterFunction::class)->constrained();
            $table->string('name');
            $table->string('number')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_employees');
    }
};
