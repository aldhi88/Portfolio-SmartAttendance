<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_logins', function (Blueprint $table) {
            if (!Schema::hasColumn('user_logins', 'print_role_name')) {
                $table->string('print_role_name')->nullable()->after('ttd');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_logins', function (Blueprint $table) {
            if (Schema::hasColumn('user_logins', 'print_role_name')) {
                $table->dropColumn('print_role_name');
            }
        });
    }
};
