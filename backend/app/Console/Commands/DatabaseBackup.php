<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\AuditLog;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'backup:database 
                            {--compress : Compress the backup file}
                            {--keep=7 : Number of days to keep backups}';

    /**
     * The console command description.
     */
    protected $description = 'Backup the database to storage';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting database backup...');

        try {
            $filename = $this->createBackup();
            $this->cleanOldBackups();

            $this->info("Backup completed: {$filename}");

            AuditLog::log('backup', 'Database', null, null, [
                'filename' => $filename,
                'size' => Storage::disk('backups')->size($filename)
            ], 'Database backup created');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Backup failed: {$e->getMessage()}");
            Log::error('Database backup failed', ['error' => $e->getMessage()]);
            return Command::FAILURE;
        }
    }

    /**
     * Create the backup file
     */
    private function createBackup(): string
    {
        $connection = config('database.default');
        $database = config("database.connections.{$connection}.database");
        $host = config("database.connections.{$connection}.host");
        $port = config("database.connections.{$connection}.port");
        $username = config("database.connections.{$connection}.username");
        $password = config("database.connections.{$connection}.password");

        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "backup_{$database}_{$timestamp}.sql";
        $filepath = storage_path("app/backups/{$filename}");

        // Ensure backup directory exists
        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        // Create backup based on database driver
        if ($connection === 'pgsql') {
            $this->backupPostgres($host, $port, $database, $username, $password, $filepath);
        } elseif ($connection === 'mysql') {
            $this->backupMysql($host, $port, $database, $username, $password, $filepath);
        } else {
            // Fallback: SQL dump using Laravel
            $this->backupGeneric($filepath);
        }

        // Compress if requested
        if ($this->option('compress')) {
            $this->compressBackup($filepath);
            $filename .= '.gz';
        }

        return $filename;
    }

    /**
     * Backup PostgreSQL database
     */
    private function backupPostgres($host, $port, $database, $username, $password, $filepath): void
    {
        $command = sprintf(
            'PGPASSWORD="%s" pg_dump -h %s -p %s -U %s -d %s -f %s',
            $password,
            $host,
            $port,
            $username,
            $database,
            $filepath
        );

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new \Exception('pg_dump failed');
        }
    }

    /**
     * Backup MySQL database
     */
    private function backupMysql($host, $port, $database, $username, $password, $filepath): void
    {
        $command = sprintf(
            'mysqldump -h %s -P %s -u %s -p%s %s > %s',
            $host,
            $port,
            $username,
            $password,
            $database,
            $filepath
        );

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new \Exception('mysqldump failed');
        }
    }

    /**
     * Generic backup using Laravel
     */
    private function backupGeneric($filepath): void
    {
        $tables = DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = ?', [
            config('database.connections.' . config('database.default') . '.database')
        ]);

        $sql = "-- Database Backup\n";
        $sql .= "-- Generated: " . now()->toDateTimeString() . "\n\n";

        foreach ($tables as $table) {
            $tableName = $table->table_name ?? $table->TABLE_NAME;

            // Get create table statement
            $sql .= "-- Table: {$tableName}\n";

            // Get data
            $rows = DB::table($tableName)->get();

            foreach ($rows as $row) {
                $values = array_map(function ($value) {
                    if (is_null($value))
                        return 'NULL';
                    return "'" . addslashes($value) . "'";
                }, (array) $row);

                $sql .= "INSERT INTO {$tableName} VALUES (" . implode(', ', $values) . ");\n";
            }

            $sql .= "\n";
        }

        file_put_contents($filepath, $sql);
    }

    /**
     * Compress the backup file
     */
    private function compressBackup($filepath): void
    {
        $gzFilepath = $filepath . '.gz';

        $fp = fopen($filepath, 'rb');
        $gz = gzopen($gzFilepath, 'wb9');

        while (!feof($fp)) {
            gzwrite($gz, fread($fp, 512000));
        }

        fclose($fp);
        gzclose($gz);

        unlink($filepath);
    }

    /**
     * Clean old backup files
     */
    private function cleanOldBackups(): void
    {
        $keepDays = (int) $this->option('keep');
        $cutoffDate = now()->subDays($keepDays);

        $backupPath = storage_path('app/backups');

        if (!is_dir($backupPath)) {
            return;
        }

        $files = glob($backupPath . '/backup_*.sql*');
        $deleted = 0;

        foreach ($files as $file) {
            $fileTime = filemtime($file);

            if ($fileTime < $cutoffDate->timestamp) {
                unlink($file);
                $deleted++;
            }
        }

        if ($deleted > 0) {
            $this->info("Cleaned up {$deleted} old backup(s)");
        }
    }
}
