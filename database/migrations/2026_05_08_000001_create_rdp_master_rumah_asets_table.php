<?php

use App\Models\RdpMasterAset;
use App\Models\RdpMasterRumah;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rdp_master_rumah_asets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(RdpMasterRumah::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(RdpMasterAset::class)
                ->constrained()
                ->restrictOnDelete();
            $table->string('jenis')->nullable();
            $table->string('jumlah')->nullable();
            $table->string('satuan')->nullable();
            $table->enum('status', ['Ada', 'Tidak Ada'])->default('Ada');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        $now = now();
        DB::table('rdp_master_rumahs')
            ->orderBy('id')
            ->get()
            ->each(function ($rumah) use ($now) {
                DB::table('rdp_master_cluster_master_asets')
                    ->where('cluster_id', $rumah->rdp_master_cluster_id)
                    ->orderBy('id')
                    ->get()
                    ->each(function ($aset) use ($rumah, $now) {
                        DB::table('rdp_master_rumah_asets')->insert([
                            'rdp_master_rumah_id' => $rumah->id,
                            'rdp_master_aset_id' => $aset->aset_id,
                            'jenis' => $aset->jenis,
                            'jumlah' => $aset->jumlah,
                            'satuan' => $aset->satuan,
                            'status' => 'Ada',
                            'catatan' => null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    });
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('rdp_master_rumah_asets');
    }
};
