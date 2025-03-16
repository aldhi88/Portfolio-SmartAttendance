<?php

namespace App\Console\Commands;

use App\Models\MasterFunction;
use Illuminate\Console\Command;

class InitMasterFunctionCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:function';

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
            ['name' => "FTM"],
            ['name' => "RSD"],
            ['name' => "MPS"],
            ['name' => "SSGA"],
            ['name' => "HSSE"],
            ['name' => "QQ"],
        ];

        foreach ($data as $key => $value) {
            $value['id'] = $key+1;
            MasterFunction::updateOrCreate($value);
        }
    }
}
