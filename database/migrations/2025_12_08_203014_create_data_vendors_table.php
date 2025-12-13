<?php

use App\Models\MasterOrganization;
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
        Schema::create('data_vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(UserLogin::class)->constrained();
            $table->foreignIdFor(MasterOrganization::class)->constrained();
            $table->string('name');
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_vendors');
    }
};
