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
            ['type' => "Card"],
            ['type' => "Fingerprint"],
            ['type' => "Face"],
        ];

        foreach ($data as $key => $value) {
            $value['id'] = $key+1;
            MasterMinor::updateOrCreate($value);
        }
    }
}
