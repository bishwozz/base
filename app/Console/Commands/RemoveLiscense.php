<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class RemoveLiscense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'college_project:production';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove license check from vendor file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = base_path('vendor/backpack/crud/src/BackpackServiceProvider.php');
        $file = $this->files->get($path);
        $file = str_replace('$this->checkLicenseCodeExists();', '//$this->checkLicenseCodeExists();', $file);
        $file = str_replace('$this->sendUsageStats();', '//$this->sendUsageStats();', $file);
        $this->files->put($path, $file);
        return 0;
    }
}
