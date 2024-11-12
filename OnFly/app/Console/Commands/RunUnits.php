<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunUnits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run-units';

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
        $this->info('Running all tests...');
        $this->info(shell_exec('php artisan test'));

        $this->info('All tests completed.');
        return Command::SUCCESS;
    }
}
