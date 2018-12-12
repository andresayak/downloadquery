<?php

namespace App\Http\Controllers;

use App\Downloads;
use App\Jobs\DownloadFile;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    public function index()
    {
        $downloads = Downloads::latest()->get();

        return view('index', compact('downloads'));
    }

    public function create()
    {
        return view('add_download');
    }

    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);
        $download = Downloads::create([
            'url' => $request->url,
            'status' => Downloads::STATUS_WAIT,
            'filepath' => Downloads::makeRandFilepath(),
        ]);

        dispatch(new DownloadFile($download));

        return redirect()->route('index');
    }

    public function download(Request $request)
    {
        $download = Downloads::findOrFail($request->id);
        if ($download && $download->isComplete()) {
            $storage = Storage::disk('public');
            $file = $storage->path($download->getPath());
            if ($storage->exists($download->getPath())) {
                return response()->download($file, $download->filename);
            }
            abort(404);
        }
        return redirect('/');
    }
}
