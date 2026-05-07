<?php

use App\Models\RdpMasterCluster;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rdp_master_rumahs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(RdpMasterCluster::class)
                ->constrained()
                ->restrictOnDelete();
            $table->string('block')->nullable();
            $table->string('tipe')->nullable();
            $table->string('nomor');
            $table->enum('status', ['Kosong', 'Terisi', 'Maintenance', 'Tidak Aktif'])->default('Kosong');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rdp_master_rumahs');
    }
};
