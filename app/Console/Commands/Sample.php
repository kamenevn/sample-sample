<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Sample extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sample:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sample test desc';

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
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $this->info('Sample test message.');
    }
}
