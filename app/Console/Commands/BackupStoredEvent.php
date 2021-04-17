<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class BackupStoredEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup Stored Events Table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $databaseName = config('database.connections.mysql.database');
        $userName = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $app = config('app.name');
        \Spatie\DbDumper\Databases\MySql::create()
            ->setDbName($databaseName)
            ->setUserName($userName)
            ->setPassword($password)
            ->includeTables(['stored_events'])
            ->dumpToFile(storage_path('app/public/stored_events.sql'));

        Storage::putFileAs($app . '/stored_events', new File(storage_path('app/public/stored_events.sql')), 'stored_events.sql');
    }
}
