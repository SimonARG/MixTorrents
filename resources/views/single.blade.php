@php
    function displayFiles($fileArray) {
        foreach ($fileArray as $key => $value) {
            if (str_contains(strval($key), '/')) {
                echo "<li class=\"sub-folder inactive\"><a class=\"sub-folder-title\"><span class=\"file-icon\">🗀</span>".substr($key, 1)."</a><ul>";
                displayFiles($fileArray[$key]);
                echo "</ul></li>";
            } else {
                echo "<li class=\"file inactive\"><span class=\"file-icon\">🗋</span>".$value['name']."<span class=\"file-size\">(".$value['size'].")</li>";
            }
        }
    }
@endphp

<x-layout>
    <div class="content-container">
        @if ($upload->user->trust === null)
            <div class="single-panel panel--default">
        @elseif ($upload->user->trust === 0)
            <div class="single-panel panel--untrusted">
        @elseif ($upload->user->trust === 1)
            <div class="single-panel panel--trusted">
        @endif
            <div class="panel-heading flex-v f-just-bet f-al-cent">
                {{ $upload->title ?? $upload->name ?? $upload->filename }}
                @can('messWithUpload', $upload)
                    <div class="single-controls flex-v">
                        <form id="up-edit" method="GET" action="{{ route('uploads.edit', $upload->id) }}">
                            @csrf
                            <button class="btn" type="submit">EDIT</button>
                        </form>
                        <form id="up-del" method="POST" action="{{ route('uploads.destroy', $upload) }}">
                            @csrf
                            @method('DELETE')
                            <button class="del-btn" type="submit">DELETE</button>
                        </form>
                    </div>
                @endcan
            </div>

            <div class="panel-body">
                <div class="upload-info flex-v">
                    <div class="flex-c panel-left">
                        <div class="flex-v">
                            <div class="flex-v single-title">
                                <div class="single-cat">Category:</div>
                            </div>
                            <div class="flex-v">
                                <div class="single-cat-val single-val"><a href="{{ "http://localhost/MixTorrents/public/search?filter=0&category=" . $upload->category->id . "_" . $upload->subcat->id . "&search=" }}">{{ $upload->category->category . " - " . $upload->subcat->subcat}}</a></div>
                            </div>
                        </div>
                        <div class="flex-v">
                            <div class="flex-v single-title">
                                <div class="single-uploader">Uploader:</div>
                            </div>
                            <div class="flex-v">
                                <div class="single-uploader-val single-val"><a href="{{ route('users.show', $upload->user) }}">{{ $upload->user->name }}</a></div>
                            </div>
                        </div>
                        <div class="flex-v">
                            <div class="flex-v single-title">
                                <div class="single-info">Information:</div>
                            </div>
                            <div class="flex-v">
                                <div class="single-info-val single-val">
                                    {{ $upload->info ?? $upload->comment }}
                                </div>
                            </div>
                        </div>
                        <div class="flex-v">
                            <div class="flex-v single-title">
                                <div class="single-size">File Size:</div>
                            </div>
                            <div class="flex-v">
                                <div class="single-size-val single-val">{{ $upload->size }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-c panel-right">
                        <div class="flex-v">
                            <div class="flex-v single-title">
                                <div class="single-date">Date:</div>
                            </div>
                            <div class="flex-v">
                                <div class="single-date-val single-val">{{ $strdate }}</div>
                            </div>
                        </div>
                        <div class="flex-v">
                            <div class="flex-v single-title">
                                <div class="single-seed">Seeders:</div>
                            </div>
                            <div class="flex-v">
                                <div class="single-seed-val single-val">{{ $upload->seeders }}</div>
                            </div>
                        </div>
                        <div class="flex-v">
                            <div class="flex-v single-title">
                                <div class="single-leech">Leechers:</div>
                            </div>
                            <div class="flex-v">
                                <div class="single-leech-val single-val">{{ $upload->leechers }}</div>
                            </div>
                        </div>
                        <div class="flex-v">
                            <div class="flex-v single-title">
                                <div class="single-down">Downloads:</div>
                            </div>
                            <div class="flex-v">
                                <div class="single-down-val single-val">{{ $upload->downloads }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-footer flex-v f-al-cent f-just-bet">
                <div>
                    <a href="{{ route('uploads.download', $upload->id) }}">🡇 Download torrent</a>
                    <span>or</span>
                    <a href="{{ $upload->magnet }}">🧲 Magnet link</a>
                </div>
                <span>Hash: <kbd>{{ $upload->hash }}</kbd></span>
                <button type="button" class="single-report-btn">Report</button>
            </div>
       </div>

       @if ($upload->description)
        <div class="single-panel panel--default">
                <div class="panel-body">{!! $upload->description !!}</></div>
        </div>
       @endif

       <div class="single-panel panel--default">
            <div class="panel-heading">File list</div>
            <div class="file-list panel-body">
                <ul class="file-ul">
                    <li>
                        <a class="folder-title">
                        <span class="file-icon">🗁</span>
                        {{ $upload->title ?? $upload->name ?? $upload->filename }}
                        </a>
                        <ul>{{ displayFiles($fileArray) }}</ul>
                    </li>
                </ul>
            </div>
       </div>

       <div class="single-panel panel--default" id="comments">
            <div class="panel-heading">
                @if ($upload->comments->first())
                    <a href="#comment-1">Comments ({{ $upload->comments->count() }})</a>
                @else
                Comments ({{ $upload->comments->count() }})
                @endif
            </div>

            <div class="comments flex-c f-al-cent">
                @foreach ($upload->comments as $comment)
                    <div class="single-panel panel--default comment-panel" id="{{ 'comment-' . $loop->iteration }}">
                        <div class="panel-body flex-v">
                            <div class="user-col flex-c f-al-cent">
                                <span>
                                    <a href="{{ route('users.show', $comment->user) }}" title="user">{{ $comment->user->name }}</a>
                                </span>
                                <div class="img-container">
                                    <img class="avatar" src="{{ url('storage/avatars/'.$comment->user->pic) }}" alt="">
                                </div>
                            </div>

                            <div class="comment-col">
                                <div class="comment-details">
                                    @if (!($comment->updated_at))
                                        <span>Created at: </span>
                                        <a href="{{ '#comment-' . $loop->iteration }}">{{ $comStrdates[$loop->index][0] }}</a></div>
                                    @elseif ($comment->updated_at)
                                        <span>Created at: </span>
                                        <a href="{{ '#comment-' . $loop->iteration }}">{{ $comStrdates[$loop->index][0] }}</a>
                                        <span> - Last updated at: </span>
                                        <a href="{{ '#comment-' . $loop->iteration }}">{{ $comUpStrdates[$loop->index][0] }}</a></div>
                                    @endif
                                <div class="comment-body">
                                    <div>{!! $comment->comment !!}</div>
                                </div>
                            </div>

                            @can("messWith-comment", $comment)
                                <div id="{{ 'comment-controls-' . $loop->iteration }}" class="comment-controls flex-v f-al-cent">
                                    <form id="comment-edit" method="GET" action="{{ route('comments.edit', $comment->id) }}">
                                        @csrf
                                        <input type="hidden" name="comment-num" value="{{ 'comment-' . $loop->iteration }}"/>
                                        <button class="com-edit-btn" type="submit">EDIT</button>
                                    </form>
                                    <form id="comment-del" method="POST" action="{{ route('comments.destroy', $comment->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="com-del-btn" type="submit">DELETE</button>
                                    </form>
                                </div>
                            @endcan
                        </div>
                    </div>
                @endforeach
                
            </div>
            
            <form class="comment-box" method="POST" action="{{ route('comments.store') }}">
                @csrf
                <div class="flex-c">
                    <label class="comment-label" for="comment">Make a comment</label>
                    <textarea class="fillable comment" id="comment" name="comment" placeholder="Type your comment..." required></textarea>
                    <input type="hidden" name="upload_id" value="{{ $upload->id }}"/>
                    <input type="hidden" name="comment-num" value="{{ $upload->comments->count() }}"/>
                </div>
                <div class="comment-submit">
                    <input class="comment-btn btn" type="submit" value="Submit">
                </div>
            </form>
       </div>
    </div>

    <script src="{{ url('../resources/js/single.js') }}"></script>
</x-layout>