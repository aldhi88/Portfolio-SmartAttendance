<?php

namespace App\Console\Commands;

use App\Models\MasterOrganization;
use Illuminate\Console\Command;

class InitMasterOrganizationCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:organization';

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
            ['name' => "PT. Pertamina Patra Niaga"],
            ['name' => "PT. PTC Mandays"],
            ['name' => "PT. PTC Volume"],
            ['name' => "PT. Patra Jasa"],
            ['name' => "PT. Patra Logistik"],
            ['name' => "PT. Prima Armada Raya"],
            ['name' => "PT. Sekurindo Duta Utama Perkasa"],
            ['name' => "Pertamedika"],
        ];

        foreach ($data as $key => $value) {
            $value['id'] = $key+1;
            MasterOrganization::updateOrCreate($value);
        }
    }
}
