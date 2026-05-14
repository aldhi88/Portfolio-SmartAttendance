<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('rdp_surat_numbers');
    }

    public function down(): void
    {
        // Tabel rdp_surat_numbers sudah tidak dipakai.
    }
};
