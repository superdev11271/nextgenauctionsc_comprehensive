<?php

namespace App\Console\Commands;

use App\Services\XeroService;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run_test';

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
        (new XeroService)->mailXeroInvoice("57d5ad1e-a5aa-4056-814f-86fc566aa402");
    }
}
