<?php

namespace App\Http\Controllers\Api\v1;

require '../vendor/bhutanio/torrent-bencode/src/Bhutanio/BEncode/BEncode.php';
require '../vendor/medariox/scrapeer/scraper.php';

use App\Models\User;
use App\Models\Upload;
use App\Helpers\fileHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\BEncode;
use App\Http\Controllers\Scraper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\ApiController;

class UploadController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);

        $uploads = $user->uploads()->latest()->paginate(5);
        
        return $this->successResponse($uploads, 'Displaying your uploads');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $upload = Upload::find($id);

        if (! $upload) {
            return $this->errorResponse('Could not find resource');
        }

        if (! Gate::allows('messWithUpload', $upload)) {
            return $this->validationErrorResponse('Access restricted');
        }

        return $this->successResponse($upload, 'Displaying upload ' . $upload->id);
    }

    public function store(Request $request)
    {
        $user = User::find(Auth::user()->id);

        if (! Gate::allows('newUpload', $user)) {
            return $this->validationErrorResponse('Your account is restricted');
        }

        // Initialize torrent decoder and scraper
        $bcoder = new BEncode;
        $scraper = new Scraper;

        // Essential validation
        $request->validate([
            'torrent_file' => ['required', File::types(['torrent', 'txt', 'text', 'conf', 'def', 'list', 'log', 'in', 'octet-stream'])],
            'category' => ['required', 'gt:0'],
            'subcat' => ['required', 'gt:0'],
            'name' => ['string', 'max:80', 'nullable'],
            'info' => ['string', 'max:28', 'nullable'],
            'description' => ['string', 'max:10000', 'nullable']
        ]);

        $dbfields['title'] = $request['name'];
        $dbfields['info'] = $request['info'];
        $dbfields['description'] = $request['description'];

        $filename =  $request->torrent_file->getClientOriginalName();

        // Store the file and get its path
        $path = $request->torrent_file->store('torrents', 'public');

        // Get the file
        $file = Storage::get('public/' . $path);

        // Decode torrent file, get hash, trackers, comment, name file list and size
        $torrent = $bcoder->bdecode($file);
        $trackers = $torrent['announce-list'];
        $comment = $torrent['comment'];
        $name = $torrent['info']['name'];
        $infohash = sha1($bcoder->bencode($torrent["info"]));
        $files = $bcoder->filelist($torrent);
        $size = $files['total_size'];

        // Create magnet link
        $magnet = 'magnet:?xt=urn:btih:' . $infohash . '&dn=' . $filename . '&tr=udp%3A%2F%2Fopen.stealth.si%3A80%2Fannounce&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce&tr=udp%3A%2F%2Fexodus.desync.com%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.torrent.eu.org%3A451%2Fannounce';

        // Flatten tracker array
        $announces = [];
        foreach ($trackers as $key => $tracker) {
            array_push($announces, $trackers[$key][0]);
        };

        // Scrape hash and tracker data
        $info = $scraper->scrape($infohash, $announces);

        // Fill database fields
        $dbfields['magnet'] = $magnet;
        $dbfields['filename'] = $filename;
        $dbfields['path'] = $path;
        $dbfields['name'] = $name;
        $dbfields['comment'] = $comment;
        $dbfields['size'] = fileHelper::formatBits($size);
        $dbfields['seeders'] = $info[$infohash]['seeders'];
        $dbfields['leechers'] = $info[$infohash]['leechers'];
        $dbfields['downloads'] = $info[$infohash]['completed'];
        $dbfields['hash'] = $infohash;
        $dbfields['user_id'] = auth()->user()->id;
        $dbfields['category_id'] = $request['category'];
        $dbfields['subcat_id'] = $request['subcat'];

        // Create database entry
        $upload = Upload::create($dbfields);

        return $this->successResponse('Upload created with id ' . $upload->id);
    }

    public function update(Request $request, string $id)
    {
    }

    public function destroy(string $id)
    {
    }
}
