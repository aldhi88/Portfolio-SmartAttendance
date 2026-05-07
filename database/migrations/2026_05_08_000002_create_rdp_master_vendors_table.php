<?php

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
        Schema::create('rdp_master_vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(UserLogin::class)->constrained();
            $table->string('nama');
            $table->string('telp');
            $table->string('alamat');
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rdp_master_vendors');
    }
};
