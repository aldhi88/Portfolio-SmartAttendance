<?php

namespace App\Console\Commands;

use App\Models\MasterPosition;
use Illuminate\Console\Command;

class InitMasterPositionCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:position';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = [
            ['name' => "Manajemen & Supervisor"],
            ['name' => "Administrasi"],
            ['name' => "Security"],
            ['name' => "Operasional & Teknik"],
            ['name' => "HSSE"],
            ['name' => "Quality & Quantity (QQ)"],
            ['name' => "P2 BBM"],
            ['name' => "Sales & General Affairs"],
            ['name' => "Maintenance & Services"],
            ['name' => "Driver"],
            ['name' => "Cleaning Services"],
            ['name' => "Medical"],
        ];

        foreach ($data as $key => $value) {
            $value['id'] = $key+1;
            MasterPosition::updateOrCreate($value);
        }
    }
}
