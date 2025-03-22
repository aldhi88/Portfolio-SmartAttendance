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
    protected $description = 'Recache laravel config.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('route:clear');
        $this->call('view:clear');

        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:cache');

        $this->info('All caches cleared and rebuilt successfully.');
    }
}
