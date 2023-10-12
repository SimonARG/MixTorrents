<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CommentController extends Controller
{
    public function store(Request $request) {
        if (Auth::check()) {
            $formFields = $request->validate([
                'comment' => 'required',
            ]);
    
            $formFields['user_id'] = auth()->user()->id;
            $formFields['upload_id'] = $request->upload_id;
            $anchor = $request['comment-num'] + 1;

            Comment::create($formFields);
    
            return Redirect::to(URL::previous() . '#comment-' . $anchor)->with('message', 'Comment posted!');
        } else {
            return Redirect::to(URL::previous())->with('message', 'You need to be logged in to comment');
        }
    }

    public function edit(Request $request, string $id) {
        $comment = Comment::find($id);

        $comDate = new DateTime($comment->created_at);
        $comStrdate = $comDate->format('Y/m/d H:i');

        if (isset($comment->updated_at)) {
            $comUpDate = new DateTime($comment->updated_at);
            $comUpStrdate = $comUpDate->format('Y/m/d H:i');
        }

        $anchor = $request['comment-num'];

        if (isset($comment->updated_at)) {
            return view('comments.edit', [
                'id' => $id,
                'comment' => $comment,
                'comStrdate' => $comStrdate,
                'comUpStrdate' => $comUpStrdate,
                'anchor' => $anchor
            ]);
        } else {
            return view('comments.edit', [
                'id' => $id,
                'comment' => $comment,
                'comStrdate' => $comStrdate,
                'anchor' => $anchor
            ]);
        }
    }

    public function destroy(string $id) {
        Comment::destroy($id);
        return back()->with('message', 'Comment deleted');
    }

    public function update(Request $request, string $id) {
        $formFields = $request->validate([
            'comment' => 'required',
        ]);

        $comment = Comment::find($id);

        $upload = $comment->upload;
        
        $comment->update([
            'comment' =>  $formFields['comment'],
        ]);

        $anchor = $request['comment-num'];

        return Redirect::to('http://localhost/MixTorrents/public/uploads/' . $upload->id . '#' . $anchor)->with('message', 'Comment edited!');
    }
}