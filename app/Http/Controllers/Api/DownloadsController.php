<?php

namespace App\Http\Controllers\Api;

use App\Downloads;
use App\Http\Resources\DownloadResource;
use App\Jobs\DownloadFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DownloadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DownloadResource::collection(Downloads::paginate(100));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return response()->json([
                "error" => 'validation error',
                "message" => $validator->errors()->first(),
            ], 422);
        }

        $download = Downloads::create([
            'url' => $request->url,
            'status' => Downloads::STATUS_WAIT,
            'filepath' => hash('crc32', uniqid())
        ]);

        dispatch(new DownloadFile($download));

        return new DownloadResource($download);
    }

    protected function validator($data)
    {
        return Validator::make($data, [
            'url' => 'required|url'
        ]);
    }
}
