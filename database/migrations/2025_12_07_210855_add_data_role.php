<?php

use App\Models\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        UserRole::create([
            'id'   => 600,
            'name' => "Vendor"
        ]);
    }

    public function down()
    {
        UserRole::where('id', 600)->delete();
    }
};
