<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class TestDatabaseConnections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:test {--connection=all : Test specific connection (mysql, sqlsrv, sqlsrv2) or all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test database connections (MySQL, SQL Server 1, SQL Server 2)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = $this->option('connection');
        
        $this->info('🔍 Testing Database Connections...');
        $this->newLine();

        $connections = [];
        
        if ($connection === 'all') {
            $connections = [
                'mysql' => 'MySQL (Default)',
                'sqlsrv' => 'SQL Server 1',
                'sqlsrv2' => 'SQL Server 2',
            ];
        } else {
            $connectionNames = [
                'mysql' => 'MySQL (Default)',
                'sqlsrv' => 'SQL Server 1',
                'sqlsrv2' => 'SQL Server 2',
            ];
            
            if (!isset($connectionNames[$connection])) {
                $this->error("❌ Invalid connection: {$connection}");
                $this->info("Available connections: mysql, sqlsrv, sqlsrv2");
                return 1;
            }
            
            $connections[$connection] = $connectionNames[$connection];
        }

        $results = [];
        
        foreach ($connections as $connName => $connLabel) {
            $this->line("Testing {$connLabel} ({$connName})...");
            
            try {
                // Get connection config
                $config = config("database.connections.{$connName}");
                
                if (!$config) {
                    $this->error("  ❌ Configuration not found for '{$connName}'");
                    $results[$connName] = false;
                    continue;
                }
                
                // Display connection info (without password)
                $this->line("  Host: {$config['host']}:{$config['port']}");
                $this->line("  Database: {$config['database']}");
                $this->line("  Username: {$config['username']}");
                
                // Test connection
                $result = DB::connection($connName)->select('SELECT 1 as test, GETDATE() as current_time');
                
                if (!empty($result)) {
                    $this->info("  ✅ Connection successful!");
                    if (isset($result[0]->current_time)) {
                        $this->line("  Server time: {$result[0]->current_time}");
                    }
                    $results[$connName] = true;
                } else {
                    $this->warn("  ⚠️  Connection successful but no data returned");
                    $results[$connName] = true;
                }
                
            } catch (Exception $e) {
                $this->error("  ❌ Connection failed!");
                $this->error("  Error: " . $e->getMessage());
                $results[$connName] = false;
            }
            
            $this->newLine();
        }

        // Summary
        $this->info('📊 Summary:');
        $this->newLine();
        
        $successCount = count(array_filter($results));
        $totalCount = count($results);
        
        foreach ($results as $connName => $success) {
            $status = $success ? '✅' : '❌';
            $this->line("  {$status} {$connName}");
        }
        
        $this->newLine();
        
        if ($successCount === $totalCount) {
            $this->info("🎉 All connections successful! ({$successCount}/{$totalCount})");
            return 0;
        } else {
            $this->warn("⚠️  Some connections failed ({$successCount}/{$totalCount} successful)");
            $this->info("💡 Please check your .env file and ensure database credentials are correct.");
            return 1;
        }
    }
}
