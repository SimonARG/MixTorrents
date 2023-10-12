<x-layout>
    <div class="content-panel">
        @auth
            @if (request('search') == Auth::user()->name)
            <h4>Displaying your uploads</h4>
            @else
            <h4>Displaying uploads for user: {{ request('search') }}</h4>
            @endif
        @else
            <h4>Displaying uploads for user: {{ request('search') }}</h4>
        @endauth
    </div>
    <div class="content-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Uploader</th>
                    <th>Category</th>
                    <th class="table-th--left">Title</th>
                    <th>Link</th>
                    <th>Size</th>
                    <th>Upload Date</th>
                    <th class="table-seeders">🡹</th>
                    <th class="table-leechers">🡻</th>
                    <th class="table-downloads">✔</th>
                    <th>💬︎</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($uploads as $upload)
                <tr>
                    @auth
                    <td class="table-uploaded table-td--center td-hide-of"><a href="{{ auth()->user()->id === $upload->user->id ? route('users.profile') : route('user.show', $upload->user) }}">{{ $upload->user->name }}</a></td>
                    @else
                    <td class="table-uploaded table-td--center td-hide-of"><a href="{{ route('user.show', $upload->user) }}">{{ $upload->user->name }}</a></td>
                    @endauth
                    <td class="table-cat table-td--center">{{ $upload->category->category }}</td>
                    <td class="table-title td-hide-of"><a href="{{ route('uploads.show', $upload->id) }}">{{ $upload->title ?? $upload->name ??  $upload->filename }}</a></td>
                    <td class="table-links table-td--center"><span class="dl-arrow"><a href="{{ route('uploads.download', $upload->id) }}">🡇</a></span><span class="dl-magnet"><a href="{{ $upload->magnet }}">🧲</a></span></td>
                    <td class="table-size table-td--center">{{ $upload->size }}</td>
                    <td class="table-date table-td--center">{{ $upStrdates[$loop->index][0] }}</td>
                    <td class="table-seeders table-td--center">{{ $upload->seeders }}</td>
                    <td class="table-leechers table-td--center">{{ $upload->leechers }}</td>
                    <td class="table-downloads table-td--center">{{ $upload->downloads }}</td>
                    <td class="table-comments table-td--center">
                    @if (!$upload->comments->first())
                    {{ $upload->comments->count() }}
                    @else
                    <a href="{{ route('uploads.show', $upload->id) . '#comments'}}">{{ $upload->comments->count() }}</a></td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>