<?php

namespace App\Console\Commands;

use App\Models\MasterLocation;
use Illuminate\Console\Command;

class InitMasterLocationCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:location';

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
            ['id' => 101, 'name' => "Integrated Terminal Dumai"],
            ['id' => 102, 'name' => "Fuel IT Dumai"],
            ['id' => 201, 'name' => "New LPG - IT Dumai"],
        ];

        foreach ($data as $key => $value) {
            MasterLocation::updateOrCreate($value);
        }
    }
}
