<?php

namespace App\Console\Commands;

use App\Models\MasterMachine;
use Illuminate\Console\Command;

class InitMasterMachineCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:machine';

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
            ['id' => 101, 'master_location_id' => 101 ,'name' => "AC-BBM-1"],
            ['id' => 201, 'master_location_id' => 201 ,'name' => "AC-LPG-1"],
        ];

        foreach ($data as $key => $value) {
            MasterMachine::updateOrCreate($value);
        }
    }
}
