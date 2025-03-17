<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AccountTrial;

class ResetTrialLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trial-limit:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cronjob Trial Accounts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        AccountTrial::truncate();

        $this->info('Tabel berhasil dikosongkan.');
    }
}
