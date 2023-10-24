<?php

namespace App\Http\Controllers;

require '../vendor/medariox/scrapeer/scraper.php';
require '../vendor/bhutanio/torrent-bencode/src/Bhutanio/BEncode/BEncode.php';

use DateTime;
use App\Models\User;
use App\Models\Subcat;
use App\Models\Upload;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\File;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Storage;
use GrahamCampbell\Markdown\Facades\Markdown;

class UploadController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Upload $upload)
    {
        $uploads = Upload::latest()->paginate(20);

        $upStrdates = [];
        foreach ($uploads as $key => $upload) {
            $upDate = new DateTime($upload->created_at);
            $upStrdate = $upDate->format('Y/m/d H:i');
            $upStrdates[$key] = [$upStrdate];
        }

        return view('home', [
            'uploads' => $uploads,
            'upStrdates' => $upStrdates
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        if (! Auth::check()) {
            return back()->with('message', 'You need to be logged in to upload');
        }

        $user = User::find(Auth::user()->id);

        if (Gate::allows('new-upload', $user)) {
            return view('upload');
        } else if (! Gate::allows('new-upload', $user)) {
            return back()->with('message', 'Your account is restricted');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        if (Auth::check()) {
            // Create array to send to database
            $dbfields = [];

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

            // Nullable validation
            // $dbfields = $request->validate([
            //     'name' => ['string', 'max:60', 'nullable'],
            //     'info' => ['string', 'max:28', 'nullable'],
            //     'description' => ['string', 'max:10000', 'nullable']
            // ]);

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

            // Format total size
            function formatBits($bits, $precision = 1) {
                $units = [' B', ' KiB', ' MiB', ' GiB', ' TiB'];

                $bits = max($bits, 0);
                $pow = floor(($bits ? log($bits) : 0) / log(1024));
                $pow = min($pow, count($units) - 1);

                $bits /= (1 << (10 * $pow));

                return round($bits, $precision) . $units[$pow];
            }

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
            $dbfields['size'] = formatBits($size);
            $dbfields['seeders'] = $info[$infohash]['seeders'];
            $dbfields['leechers'] = $info[$infohash]['leechers'];
            $dbfields['downloads'] = $info[$infohash]['completed'];
            $dbfields['hash'] = $infohash;
            $dbfields['user_id'] = auth()->user()->id;
            $dbfields['category_id'] = $request['category'];
            $dbfields['subcat_id'] = $request['subcat'];

            // Create database entry
            $upload = Upload::create($dbfields);

            // Redirect to upload with success message
            return redirect('uploads/' . $upload->id)->with('message', 'Upload successful!');
        } else {
            return back()->with('message', 'You need to be logged in to upload');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Upload $upload) {
        // Check for description field in DB and convert it to Markdown if it exists
        if ($upload->description) {
            $upload->description = Markdown::convert($upload->description)->getContent();
        };

        // Check for comments in DB and format their creation and update dates
        if ($upload->comments->first()) {
            $comStrdates = [];
            foreach ($upload->comments as $key => $comment) {
                $comment->comment = Markdown::convert($comment->comment)->getContent();
                
                $comDate = new DateTime($comment->created_at);
                $comStrdate = $comDate->format('Y/m/d H:i');
                $comStrdates[$key] = [$comStrdate];

                if (isset($comment->updated_at)) {
                    $comUpDate = new DateTime($comment->updated_at);
                    $comUpStrdate = $comUpDate->format('Y/m/d H:i');
                    $comUpStrdates[$key] = [$comUpStrdate];
                }
            }
        }

        // Get and format date
        $date = new DateTime($upload->created_at);
        $strdate = $date->format('Y/m/d H:i');

        // Get file
        $file = Storage::get('public/' . $upload['path']);

        // Initialize torrent decoder
        $bcoder = new BEncode;

        // Decode torrent file and get file list
        $torrent = $bcoder->bdecode($file);
        $fileList = $bcoder->filelist($torrent);
        $files = $fileList['files'];

        // Format total size
        function formatBits($bits, $precision = 1) {
            $units = [' B', ' KiB', ' MiB', ' GiB', ' TiB'];

            $bits = max($bits, 0);
            $pow = floor(($bits ? log($bits) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);

            $bits /= (1 << (10 * $pow));

            return round($bits, $precision) . $units[$pow];
        }

        // Make file list array
        $fileArray = [];
        foreach ($files as $file) {
            if (str_contains($file['name'], '/')) {
                $split = explode('/', $file['name']);
                $name = last($split);
                $path = array_chunk($split, sizeof($split) - 1)[0];
            } else {
                $name = $file['name'];
                $path = [];
            }
            $temp = [['name' => $name, 'size' => formatBits($file['size'])]];
            while (count($path) > 0) {
                $temp2 = [];
                $temp2['/' . last($path)] = $temp;
                array_pop($path);
                $temp3 = $temp2;
                $temp2 = $temp;
                $temp = $temp3;
            }
            $fileArray = array_merge_recursive($fileArray, $temp);
        }

        // Sort file list array
        function sortFileStructure(&$array) {
            $folders = [];
            $files = [];

            foreach ($array as $key => &$element) {
                if (is_array($element)) {
                    if (isset($element['name'])) {
                        $files[$key] = $element;
                    } else {
                        sortFileStructure($element);
                        $folders[$key] = $element;
                    }
                }
            }

            ksort($folders);
            $array = $folders + $files;
        }

        sortFileStructure($fileArray);

        // Return view depending on prescence of comments
        if (isset($comStrdates)) {
            if (isset($comUpStrdates)) {
                return view('single', [
                    'upload' => $upload,
                    'strdate' => $strdate,
                    'comStrdates' => $comStrdates,
                    'comUpStrdates' => $comUpStrdates,
                    'fileArray' => $fileArray
                ]);
            } else { 
                return view('single', [
                    'upload' => $upload,
                    'strdate' => $strdate,
                    'comStrdates' => $comStrdates,
                    'fileArray' => $fileArray
                ]);
            }
        } else {
            return view('single', [
                'upload' => $upload,
                'strdate' => $strdate,
                'fileArray' => $fileArray
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        $upload = Upload::find($id);

        if (! Gate::allows('messWith-upload', $upload)) {
            abort(403);
        }

        // Get file
        $file = Storage::get('public/' . $upload['path']);

        return view('uploads.edit', [
            'id' => $id,
            'upload' => $upload,
            'file' => $file
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        $upload = Upload::find($id);

        if (! Gate::allows('messWith-upload', $upload)) {
            abort(403);
        }

        // Get the file
        $upFile = Storage::get('public/' . $upload->path);

        // Create array to send to database
        $dbfields = [];

        // Initialize torrent decoder and scraper
        $bcoder = new BEncode;
        $scraper = new Scraper;

        // Essential validation
        $request->validate([
            'torrent_file' => [File::types(['torrent', 'txt', 'text', 'conf', 'def', 'list', 'log', 'in', 'octet-stream'])],
            'category' => ['required', 'gt:0'],
            'subcat' => ['required', 'gt:0'],
            'name' => ['string', 'max:80', 'nullable'],
            'info' => ['string', 'max:28', 'nullable'],
            'description' => ['string', 'max:10000', 'nullable']
        ]);

        if (!($request['torrent_file'])) {
            $request['torrent_file'] = $upFile;
        }

        $dbfields['title'] = $request['name'];
        $dbfields['info'] = $request['info'];
        $dbfields['description'] = $request['description'];

        if ($upFile) {
            $filename = $upload->filename;
        } else {
            $filename =  $request->torrent_file->getClientOriginalName();
        }

        // Store the file and get its path
        
        if ($upFile) {
            $path = $upload->path;
        } else {
            $path = $request->torrent_file->store('torrents', 'public');
        }

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

        // Format total size
        function formatBits($bits, $precision = 1)
        {
            $units = [' B', ' KiB', ' MiB', ' GiB', ' TiB'];

            $bits = max($bits, 0);
            $pow = floor(($bits ? log($bits) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);

            $bits /= (1 << (10 * $pow));

            return round($bits, $precision) . $units[$pow];
        }

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
        $dbfields['size'] = formatBits($size);
        $dbfields['seeders'] = $info[$infohash]['seeders'];
        $dbfields['leechers'] = $info[$infohash]['leechers'];
        $dbfields['downloads'] = $info[$infohash]['completed'];
        $dbfields['hash'] = $infohash;
        $dbfields['category_id'] = $request['category'];
        $dbfields['subcat_id'] = $request['subcat'];

        // Update database entry
        $updated = $upload->update([
            'magnet' => $dbfields['magnet'],
            'filename' => $dbfields['filename'],
            'path' => $dbfields['path'],
            'name' => $dbfields['name'],
            'comment' => $dbfields['comment'],
            'size' => $dbfields['size'],
            'seeders' => $dbfields['seeders'],
            'leechers' => $dbfields['leechers'],
            'downloads' => $dbfields['downloads'],
            'hash' => $dbfields['hash'],
            'category_id' => $dbfields['category_id'],
            'subcat_id' => $dbfields['subcat_id'],
            'title' => $dbfields['title'],
            'info' => $dbfields['info'],
            'description' => $dbfields['description']
        ]);

        // Redirect to upload with success message
        return redirect('uploads/' . $upload->id)->with('message', 'Torrent updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Upload::destroy($id);
        return to_route('torrents.index')->with('message', 'Upload deleted');
    }

    public function download(Upload $upload)
    {
        $path = public_path('storage/' . $upload->path);
        return response()->download($path, $upload->filename);
    }

    /**
     * Search for a resource
     */
    public function search(Request $request){
        if ($request->category == 0) {
            if ($request->filter == 0) {
                $uploads = Upload::whereExists(function (Builder $query) {
                    $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('title', 'like', '%' . request('search') . '%')
                    ->orWhere('filename', 'like', '%' . request('search') . '%');
                })
                ->latest()
                ->paginate(20);
            } else if ($request->filter == 1) {
                $uploads = Upload::whereRelation('user', 'trust', 1)
                ->whereExists(function (Builder $query) {
                    $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('title', 'like', '%' . request('search') . '%')
                    ->orWhere('filename', 'like', '%' . request('search') . '%');
                })
                ->latest()
                ->paginate(20);
            }
        } else {
            $categoriesString = $request->category;

            if (strlen(trim($categoriesString)) > 1) {
                $categories = explode("_", $request->category);

                if ($request->filter == 0) {
                    $uploads = Upload::whereExists(function (Builder $query) {
                        $query->where('name', 'like', '%' . request('search') . '%')
                            ->orWhere('title', 'like', '%' . request('search') . '%')
                            ->orWhere('filename', 'like', '%' . request('search') . '%');
                        })
                        ->where('category_id', $categories[0])
                        ->where('subcat_id', $categories[1])
                        ->latest()
                        ->paginate(20);

                    $viewCat = Category::find($categories[0]);
                    $viewSubcat = Subcat::find($categories[1]);
                } else if ($request->filter == 1) {
                    $uploads = Upload::whereRelation('user', 'trust', 1)
                            ->whereExists(function (Builder $query) {
                                $query->where('name', 'like', '%' . request('search') . '%')
                                    ->orWhere('title', 'like', '%' . request('search') . '%')
                                    ->orWhere('filename', 'like', '%' . request('search') . '%');
                            })
                            ->where('category_id', $categories[0])
                            ->where('subcat_id', $categories[1])
                            ->latest()
                            ->paginate(20);

                    $viewCat = Category::find($categories[0]);
                    $viewSubcat = Subcat::find($categories[1]);
                }
            } else {
                $category = $request->category;

                if ($request->filter == 0) {
                    $uploads = Upload::whereExists(function (Builder $query) {
                                $query->where('name', 'like', '%' . request('search') . '%')
                                    ->orWhere('title', 'like', '%' . request('search') . '%')
                                    ->orWhere('filename', 'like', '%' . request('search') . '%');
                            })
                            ->where('category_id', $category[0])
                            ->latest()
                            ->paginate(20);

                    $viewCat = Category::find($category);
                } else if ($request->filter == 1) {
                    $uploads = Upload::whereRelation('user', 'trust', 1)
                    ->whereExists(function (Builder $query) {
                        $query->where('name', 'like', '%' . request('search') . '%')
                            ->orWhere('title', 'like', '%' . request('search') . '%')
                            ->orWhere('filename', 'like', '%' . request('search') . '%');
                    })
                    ->where('category_id', $category[0])
                    ->latest()
                    ->paginate(20);

                    $viewCat = Category::find($category);
                }
            }
        }

        $upStrdates = [];
        foreach ($uploads as $key => $upload) {
            $upDate = new DateTime($upload->created_at);
            $upStrdate = $upDate->format('Y/m/d H:i');
            $upStrdates[$key] = [$upStrdate];
        }

        if (isset($viewCat) && !(isset($viewSubcat))) {
            $viewCats = $viewCat->category;
        } else if (isset($viewCat) && isset($viewSubcat)) {
            $viewCats = $viewCat->category . ' - ' . $viewSubcat->subcat;
        }

        if (isset($viewCats)) {
            return view('results', [
                'uploads' => $uploads,
                'upStrdates' => $upStrdates,
                'viewCats' => $viewCats
            ]);
        } else {
            return view('results', [
                'uploads' => $uploads,
                'upStrdates' => $upStrdates
            ]);
        }
    }
}
