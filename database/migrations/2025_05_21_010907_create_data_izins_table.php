<?php

use App\Models\DataEmployee;
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
        Schema::create('data_izins', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DataEmployee::class)->constrained();
            $table->enum('jenis',['Sakit','Keluar','Pulang','Dinas']);
            $table->timestamp('from');
            $table->timestamp('to');
            $table->text('desc')->nullable();
            $table->text('bukti')->nullable();
            $table->enum('status', ['Proses','Disetujui','Ditolak']);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_izins');
    }
};
