<?php

namespace App\Console\Commands;

use App\Models\MasterMinor;
use Illuminate\Console\Command;

class InitMasterMinorCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:minor';

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
            ['id'=>1, 'type' => "Card"],
            ['id'=>38, 'type' => "Fingerprint"],
            ['id'=>75, 'type' => "Face"],
        ];

        foreach ($data as $key => $value) {
            MasterMinor::updateOrCreate($value);
        }
    }
}
