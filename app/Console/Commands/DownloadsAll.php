<?php

namespace App\Console\Commands;

use App\Downloads;
use App\Http\Resources\DownloadResource;
use Illuminate\Console\Command;

class DownloadsAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'downloads:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View all downloads';

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
        $headers = ['Id', 'Url', 'Status', 'Size Mb', 'Download link'];
        $downloads = Downloads::latest()
            ->get(['id', 'url', 'status', 'filesize'])
            ->transform(function ($download) {
                $download->download_link = $download->downloadLink();
                $download->filesize = $download->getSize();
                return $download;
            })
            ->toArray();
        $this->table($headers, $downloads);
    }
}
