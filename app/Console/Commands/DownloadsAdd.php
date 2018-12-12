<?php

namespace App\Console\Commands;

use App\Downloads;
use App\Jobs\DownloadFile;
use Illuminate\Console\Command;

class DownloadsAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'downloads:add {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add url to download';

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
        $url = $this->argument('url');
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            $this->error('url is not valid');
            return;
        }
        $download = Downloads::create([
            'url' => $url,
            'status' => Downloads::STATUS_WAIT,
            'filepath' => Downloads::makeRandFilepath(),
        ]);

        dispatch(new DownloadFile($download));
        $this->line('Done');
    }
    
}
