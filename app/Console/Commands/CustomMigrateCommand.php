<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CustomMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:custom-migrate-command';

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
        // $migrations = [
        //     '2024_05_10_221016_create__companies_table.php',
        //     '2023_11_18_091333_create__servises_table.php',
        //     // '2014_10_12_000000_create_users_table.php',
        //     // '2014_10_12_100000_create_password_reset_tokens_table.php',
        //     // '2014_10_12_100000_create_password_resets_table.php',
        //     // '2014_10_12_100000_create_password_reset_tokens_table.php',
        //     // '2023_11_16_122739_create_cities_table.php',
        // ];

        // foreach ($migrations as $migration) {
        //     $basePath = 'database/migrations/';
        //     $migrationName = trim($migration);
        //     $path = $basePath. $migrationName;
        //     $this->call('migrate:refresh', ['--path' => $path]);
        // }
    }
}
