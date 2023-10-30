<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use App\Models\Subcat;
use App\Models\Upload;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        if ($request->category == 0) {
            if ($request->filter == 0) {
                $uploads = Upload::whereExists(function (Builder $query) {
                    $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('title', 'like', '%' . request('search') . '%')
                    ->orWhere('filename', 'like', '%' . request('search') . '%');
                })
                ->latest()
                ->paginate(20);
            } elseif ($request->filter == 1) {
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
                } elseif ($request->filter == 1) {
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
                } elseif ($request->filter == 1) {
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
        } elseif (isset($viewCat) && isset($viewSubcat)) {
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

    public function uploads(Request $request)
    {
        $user = User::where($request->field, $request->name)->first();
 
        $uploads = $user->uploads()->latest()->paginate(20);

        $upStrdates = [];
        foreach ($uploads as $key => $upload) {
            $upDate = new DateTime($upload->created_at);
            $upStrdate = $upDate->format('Y/m/d H:i');
            $upStrdates[$key] = [$upStrdate];
        }
        
        return view('users.uploads', [
            'uploads' => $uploads,
            'upStrdates' => $upStrdates
        ]);
    }

}
