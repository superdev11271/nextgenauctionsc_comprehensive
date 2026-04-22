<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class ImportSQLCommand extends Command
{
    protected $signature = 'db:import {file} {--fresh}';
    protected $description = 'Import an SQL file into the database with an option to migrate fresh';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $filePath = $this->argument('file');
        $fresh = $this->option('fresh');

        if ($fresh) {
            $this->info('Running fresh migrations...');
            Artisan::call('migrate:fresh');
            $this->info('Migrations completed.');
        }

        if (!File::exists($filePath)) {
            $this->error("File not found: $filePath");
            return 1;
        }

        $sql = File::get($filePath);
        $statements = $this->splitSQL($sql);

        foreach ($statements as $statement) {
            try {
                DB::unprepared($statement);
            } catch (\Exception $e) {
                $this->error("Error executing statement: $statement");
                $this->error($e->getMessage());
                return 1;
            }
        }

        $this->info('Database import completed successfully.');
        return 0;
    }

    protected function splitSQL($sql)
    {
        $delimiter = ';';
        $lines = explode("\n", $sql);
        $statements = [];
        $statement = '';

        foreach ($lines as $line) {
            $trimmedLine = trim($line);

            // Skip comments and empty lines
            if ($trimmedLine === '' || strpos($trimmedLine, '--') === 0 || strpos($trimmedLine, '/*') === 0) {
                continue;
            }

            // Handle delimiter change
            if (stripos($trimmedLine, 'DELIMITER') === 0) {
                $parts = preg_split('/\s+/', $trimmedLine);
                $delimiter = end($parts);
                continue;
            }

            $statement .= $line . "\n";

            if (substr(rtrim($line), -1 * strlen($delimiter)) === $delimiter) {
                $statements[] = rtrim($statement, $delimiter);
                $statement = '';
            }
        }

        return $statements;
    }

}
