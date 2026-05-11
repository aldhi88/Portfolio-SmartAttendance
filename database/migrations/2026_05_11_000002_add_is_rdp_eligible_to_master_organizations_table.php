<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('master_organizations', 'is_rdp_eligible')) {
            Schema::table('master_organizations', function (Blueprint $table) {
                $table->boolean('is_rdp_eligible')->default(false)->after('name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('master_organizations', 'is_rdp_eligible')) {
            Schema::table('master_organizations', function (Blueprint $table) {
                $table->dropColumn('is_rdp_eligible');
            });
        }
    }
};
