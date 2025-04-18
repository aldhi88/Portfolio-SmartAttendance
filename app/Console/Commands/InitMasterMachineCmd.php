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
            ['name' => "AC-BBM-1"],
            ['name' => "AC-LPG-1"],
            ['name' => "Mesin 3"],
            ['name' => "Mesin 4"],
        ];

        foreach ($data as $key => $value) {
            $value['id'] = $key+1;
            MasterMachine::updateOrCreate($value);
        }
    }
}
