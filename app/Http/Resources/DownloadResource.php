<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DownloadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'status' => $this->status,
            'filename' => $this->filename,
            'filesize' => $this->filesize,
            'error_msg' => $this->error_msg,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'download_link' => $this->downloadLink()
        ];
    }
}
