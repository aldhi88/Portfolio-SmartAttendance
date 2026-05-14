<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_logins', function (Blueprint $table) {
            if (!Schema::hasColumn('user_logins', 'ttd')) {
                $table->string('ttd')->nullable()->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_logins', function (Blueprint $table) {
            if (Schema::hasColumn('user_logins', 'ttd')) {
                $table->dropColumn('ttd');
            }
        });
    }
};
