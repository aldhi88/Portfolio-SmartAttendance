<?php

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
        Schema::table('data_schedules_bebas', function (Blueprint $table) {
            /**
             * 1) Drop FK yang "membutuhkan" index ini.
             * Nama FK default Laravel biasanya:
             * data_schedules_bebas_master_schedule_id_foreign
             *
             * Kalau namanya beda, ganti string di bawah sesuai nama FK di phpMyAdmin.
             */
            $table->dropForeign('data_schedules_bebas_master_schedule_id_foreign');

            // 2) Drop unique lama (master_schedule_id + tanggal)
            $table->dropUnique('unique_schedule_date');

            // 3) Add unique baru (master_schedule_id + data_employee_id + tanggal)
            $table->unique(
                ['master_schedule_id', 'data_employee_id', 'tanggal'],
                'unique_emp_master_date'
            );

            // 4) Add FK lagi
            $table->foreign('master_schedule_id')
                ->references('id')
                ->on('master_schedules')
                ->cascadeOnDelete(); // sesuaikan kalau sebelumnya bukan cascade
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
