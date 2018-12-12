<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Downloads extends Model
{
    const STATUS_WAIT = 'pending';
    const STATUS_ERROR = 'error';
    const STATUS_SUCCESS = 'complete';
    const STATUS_DOWNLOADING = 'downloading';

    protected $statuses = [
        self::STATUS_WAIT   =>  'pending',
        self::STATUS_ERROR  =>  'error',
        self::STATUS_SUCCESS  =>  'complete',
        self::STATUS_DOWNLOADING => 'downloading'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['filename', 'filesize', 'status', 
        'filepath', 'error_msg', 'url'];
    
    protected $guarded = [];

    public function getStatus()
    {
        return $this->statuses[$this->status];
    }

    public function isComplete()
    {
        if ($this->status == self::STATUS_SUCCESS) {
            return true;
        }
        return false;
    }

    public function getPath()
    {
        if ($this->status == self::STATUS_SUCCESS && $this->filename) {
            return $this->filepath. '/downloadfile';
        }
        return null;
    }

    public function getSize()
    {
        if ($this->filesize) {
            return round((int)$this->filesize / (1024*1024), 2);
        }
        return null;
    }

    public function downloadLink()
    {
        if ($this->isComplete()) {
            return route('download', ['id' => $this->id]);
        }
        return null;
    }
    
    static function makeRandFilepath()
    {
        return substr(hash('crc32', uniqid()), 0, 6);
    }
    
}
