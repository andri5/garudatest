<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class CreateSqlServerDatabases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create-sqlserver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create SQL Server databases if they do not exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Creating SQL Server databases...');
        $this->newLine();

        // SQL Server 1
        $this->line('Creating database for SQL Server 1...');
        try {
            $config1 = config('database.connections.sqlsrv');
            $dsn1 = "sqlsrv:Server={$config1['host']},{$config1['port']};Database=master;TrustServerCertificate=1";
            $pdo1 = new \PDO($dsn1, $config1['username'], $config1['password']);
            $pdo1->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            $dbName1 = $config1['database'];
            $pdo1->exec("IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = '{$dbName1}') CREATE DATABASE [{$dbName1}]");
            $this->info("  ✅ Database '{$dbName1}' created or already exists");
        } catch (Exception $e) {
            $this->error("  ❌ Error: " . $e->getMessage());
        }

        $this->newLine();

        // SQL Server 2
        $this->line('Creating database for SQL Server 2...');
        try {
            $config2 = config('database.connections.sqlsrv2');
            $dsn2 = "sqlsrv:Server={$config2['host']},{$config2['port']};Database=master;TrustServerCertificate=1";
            $pdo2 = new \PDO($dsn2, $config2['username'], $config2['password']);
            $pdo2->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            $dbName2 = $config2['database'];
            $pdo2->exec("IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = '{$dbName2}') CREATE DATABASE [{$dbName2}]");
            $this->info("  ✅ Database '{$dbName2}' created or already exists");
        } catch (Exception $e) {
            $this->error("  ❌ Error: " . $e->getMessage());
        }

        $this->newLine();
        $this->info('✅ Done!');
        
        return 0;
    }
}
