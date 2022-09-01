<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InitDb extends Command
{
    protected $signature = 'init-db';

    protected $description = 'Инициализировать БД';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $database = config('database.connections.mysql.database');
        $charset = config('database.connections.mysql.charset');
        $collation = config('database.connections.mysql.collation');

        config([
            'database.connections.mysql.database' => null,
        ]);

        DB::statement("CREATE DATABASE IF NOT EXISTS {$database} CHARACTER SET {$charset} COLLATE {$collation}");

        config([
            'database.connections.mysql.database' => $database,
        ]);
    }
}
