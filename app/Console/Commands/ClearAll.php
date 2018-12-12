<?php

namespace App\Console\Commands;

use App\Downloads;
use App\Http\Resources\DownloadResource;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all downloads';

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
     * @return mixed
     */
    public function handle()
    {
        $storage = Storage::disk('public');
        $folders = $storage->allDirectories();
        foreach($folders AS $folder)
            $storage->deleteDirectory($folder);
        
        $this->line('Done');
    }
}
