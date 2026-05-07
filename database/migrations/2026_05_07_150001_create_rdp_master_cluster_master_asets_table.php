<?php

use App\Models\RdpMasterAset;
use App\Models\RdpMasterCluster;
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
        Schema::create('rdp_master_cluster_master_asets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(RdpMasterCluster::class, 'cluster_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(RdpMasterAset::class, 'aset_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('jenis')->nullable();
            $table->string('jumlah')->nullable();
            $table->string('satuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rdp_master_cluster_master_asets');
    }
};
