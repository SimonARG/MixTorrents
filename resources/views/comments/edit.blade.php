<x-layout>
    <div class="content-container">
        <div class="single-panel panel--default comment-panel comment-panel--edit">
            <h1 class="page-title">Edit your comment</h1>
            <form class="flex-c f-just-cent" method="POST" action="{{ route('comments.update', $comment->id) }}">
                @csrf
                @method('PATCH')

                <div class="panel-body flex-v">
                    <div class="user-col flex-c f-al-cent">
                        <span>
                            <a href="{{ route('users.show', $comment->user) }}" title="user">{{ $comment->user->name }}</a>
                        </span>
                        <div class="img-container">
                            <img class="avatar" src="{{url('storage/avatars/'.$comment->user->pic)}}" alt="">
                        </div>
                    </div>

                    <div class="comment-col">
                        <div class="comment-details">
                            @if (!($comment->updated_at))
                                <span>Created at: </span>
                                <a href="#">{{ $comStrdate }}</a></div>
                            @elseif ($comment->updated_at)
                            <span>Created at: </span>
                                <a href="#">{{ $comStrdate }}</a>
                                <span> - Last updated at: </span>
                                <a href="#">{{ $comUpStrdate }}</a></div>
                            @endif
                        <div class="comment-body">
                            <textarea class="fillable comment" id="comment" name="comment" required>{{ $comment->comment }}</textarea>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="comment-num" value="{{ $anchor }}"/>

                <input class="comment-btn btn" type="submit" value="Submit">
            </form>
        </div>
    </div>
</x-layout>