<?php

namespace App\Jobs;

use App\Downloads;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class DownloadFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $download;

    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Downloads $download)
    {
        $this->download = $download;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->download->status = Downloads::STATUS_DOWNLOADING;
        $this->download->filesize = $this->getSize();
        $this->download->save();
        $url = $this->download->url;
        $fileName = pathinfo($url, PATHINFO_BASENAME);
        $file = Storage::disk('local')->path('temp/'). $this->download->filepath;
        $fp = fopen ($file, 'w+');
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600*6);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $result = curl_exec($ch);
        fclose($fp);

        if ($result !== false) {
            Storage::move('temp/'. $this->download->filepath, 'public/'. $this->download->filepath. '/downloadfile');
            $this->download->filename = $fileName;
            $this->download->status = Downloads::STATUS_SUCCESS;
        }else {
            $this->download->status = Downloads::STATUS_ERROR;
            $this->download->error_msg = curl_error($ch);
        }
        curl_close($ch);
        $this->download->save();
    }

    public function failed(\Exception $exception)
    {
        $this->download->status = Downloads::STATUS_ERROR;
        $this->download->error_msg = $exception->getMessage();
        $this->download->save();
    }

    protected function getSize()
    {
        $result = null;
        $ch = curl_init( $this->download->url );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_NOBODY, true );
        curl_setopt($ch, CURLOPT_HEADER, true );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        $data = curl_exec($ch);
        curl_close($ch);
        if($data) {
            $content_length = "";
            $status = "";
            if (preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches )) {
                $status = (int)$matches[1];
            }
            if (preg_match( "/Content-Length: (\d+)/", $data, $matches )) {
                $content_length = (int)$matches[1];
            }
            if ($status == 200 || ($status > 300 && $status <= 308)) {
                $result = $content_length;
            }
        }
        return $result;
    }
    
}
