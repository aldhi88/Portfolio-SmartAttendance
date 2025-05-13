<?php

use App\Models\MasterFunction;
use App\Models\MasterLocation;
use App\Models\MasterOrganization;
use App\Models\MasterPosition;
use App\Models\UserLogin;
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
            $table->foreignIdFor(UserLogin::class)->nullable()->constrained();
            $table->foreignIdFor(MasterOrganization::class)->nullable()->constrained();
            $table->foreignIdFor(MasterPosition::class)->nullable()->constrained();
            $table->foreignIdFor(MasterLocation::class)->nullable()->constrained();
            $table->foreignIdFor(MasterFunction::class)->nullable()->constrained();
            $table->string('name');
            $table->string('number')->default('-');
            $table->enum('status',['Belum Aktif', 'Aktif', 'Tidak Aktif'])->default('Belum Aktif');
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
