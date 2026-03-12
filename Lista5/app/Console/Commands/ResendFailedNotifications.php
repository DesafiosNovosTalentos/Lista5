<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResendFailedNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:retry-failed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testando comando';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("testando comando artisan");
    }
}
