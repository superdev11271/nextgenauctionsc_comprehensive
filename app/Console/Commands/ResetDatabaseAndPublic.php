<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetDatabaseAndPublic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:reset_database_and_public_directory {publiczipfilepath} {mysqlfilepath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $publiczipfilepath = $this->argument('publiczipfilepath');
        $mysqlfilepath = $this->argument('mysqlfilepath');

        $this->deleteDirectory(base_path().'/public');
        $this->extract($publiczipfilepath,base_path());
        $this->dropTables();
        $this->importSql($mysqlfilepath);
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return Command::SUCCESS;
    }
    public function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            echo "not a directory";
            return false;
        }
        $items = array_diff(scandir($dir), array('.', '..'));
        foreach ($items as $item) {
            $path = "$dir/$item";
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path); // Delete file
            }
        }
        return rmdir($dir);
    }
    public function extract($zipFile, $extractTo)
    {

        $zip = new \ZipArchive;
        if ($zip->open($zipFile) === TRUE) {
            $zip->extractTo($extractTo);
            $zip->close();
            echo 'ZIP file extracted successfully.';
        } else {
            echo 'Failed to open the ZIP file.';
        }
    }
    public function importSql($file)
    {
        if (!file_exists($file)) {
            $this->error('The specified file does not exist.');
            return 1;
        }

        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        // Construct the mysql command
        $command = sprintf(
            'mysql -u %s -p%s %s < %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            escapeshellarg($file)
        );

        // Execute the command
        $output = null;
        $returnVar = null;
        exec($command, $output, $returnVar);

        // Check if the command was successful
        if ($returnVar !== 0) {
            $this->error('Failed to import the database.');
            return $returnVar;
        }

        $this->info('Database imported successfully.');
        return 0;
    }
    public function dropTables()
    {

        $tables = DB::select('SHOW TABLES');

        $tables = array_map('current', $tables);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($tables as $table) {
            Schema::drop($table);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('All tables dropped successfully.');

        return 0;
    }
}
