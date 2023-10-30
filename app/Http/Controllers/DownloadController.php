<?php

namespace App\Http\Controllers;

use App\Models\Upload;

class DownloadController extends Controller {

    public function __invoke(Upload $upload) {
        $path = public_path('storage/' . $upload->path);
        return response()->download($path, $upload->filename);
    }

}