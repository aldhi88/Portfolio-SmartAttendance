<?php

namespace App\Console\Commands;

use App\Models\DataLov;
use Illuminate\Console\Command;

class initGpsCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init-gps-cmd';

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
        $q = DataLov::where('key', 'koordinat')->get()->count();

        if ($q == 0) {
            $data = [
                'key' => "koordinat",
                'value' => [
                    [
                        'name' => "Kantor BBM",
                        'lat' => 1.6417630640181198,
                        'lng' => 101.4475468306074,
                        'radius' => 250
                    ],
                    [
                        'name' => "Kantor LPG",
                        'lat' => 1.6902533609125676,
                        'lng' => 101.42906750721225,
                        'radius' => 350
                    ],
                ],
            ];
            // dd($data);
            DataLov::create($data);
        }
    }
}
