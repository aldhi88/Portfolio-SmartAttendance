<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppInitCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init';

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
        $this->call('init:user');
        $this->call('init:organization');
        $this->call('init:position');
        $this->call('init:location');
        $this->call('init:function');
        $this->call('init:minor');
        $this->call('init:machine');

        $this->info('Initiation Complate');
    }
}
