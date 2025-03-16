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
            ['name' => "Integrated Terminal Dumai"],
            ['name' => "Fuel IT Dumai"],
            ['name' => "New LPG - IT Dumai"],
        ];

        foreach ($data as $key => $value) {
            $value['id'] = $key+1;
            MasterLocation::updateOrCreate($value);
        }
    }
}
