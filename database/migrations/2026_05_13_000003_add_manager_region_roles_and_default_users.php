<?php

use App\Models\UserLogin;
use App\Models\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        UserRole::updateOrCreate(['id' => 800], ['name' => 'Manager HC Region']);
        UserRole::updateOrCreate(['id' => 900], ['name' => 'Manager Aset Region']);

        UserLogin::withTrashed()->updateOrCreate(
            ['user_role_id' => 800],
            [
                'nickname' => 'Manager HC Region',
                'username' => 'mhcr',
                'password' => Hash::make('rahasia'),
                'deleted_at' => null,
            ]
        );

        UserLogin::withTrashed()->updateOrCreate(
            ['user_role_id' => 900],
            [
                'nickname' => 'Manager Aset Region',
                'username' => 'mar',
                'password' => Hash::make('rahasia'),
                'deleted_at' => null,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        UserLogin::whereIn('user_role_id', [800, 900])->forceDelete();
        UserRole::where('id', 900)->delete();
    }
};
