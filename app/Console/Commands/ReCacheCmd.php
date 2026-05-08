<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReCacheCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild all production caches.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing existing caches...');
        $this->call('optimize:clear');

        $this->info('Rebuilding production caches...');
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:cache');
        $this->call('event:cache');

        $this->info('Production caches cleared and rebuilt successfully.');
    }
}
