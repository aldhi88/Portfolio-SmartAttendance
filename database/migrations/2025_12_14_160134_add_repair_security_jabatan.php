<?php

use App\Models\DataEmployee;
use App\Models\MasterPosition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            MasterPosition::query()
                ->insertOrIgnore([
                    'id' => 100,
                    'name' => 'Security',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            MasterPosition::query()
                ->insertOrIgnore([
                    'id' => 101,
                    'name' => 'Kepala Security',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            DataEmployee::query()
                ->where('master_position_id', 3)
                ->update(['master_position_id' => 100]);
            MasterPosition::where('id', 3)->delete();
        });
    }

    public function down(): void
    {
        DB::transaction(function () {
            MasterPosition::withTrashed()
                ->where('id', 3)
                ->restore();

            DataEmployee::query()
                ->where('master_position_id', 100)
                ->update(['master_position_id' => 3]);
            MasterPosition::query()
                ->where('id', 101)
                ->where('name', 'Kepala Security')
                ->forceDelete();
            MasterPosition::query()
                ->where('id', 100)
                ->where('name', 'Security')
                ->forceDelete();
        });
    }
};
