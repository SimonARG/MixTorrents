<x-layout>
    <div class="content-container">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-uploader">Uploader</th>
                    <th class="col-category">Category</th>
                    <th class="col-title table-th--left">Title</th>
                    <th class="col-link title-link">Link</th>
                    <th class="col-size">Size</th>
                    <th class="col-date">Upload Date</th>
                    <th class="col-seed table-seeders">🡹</th>
                    <th class="col-leech table-leechers">🡻</th>
                    <th class="col-down table-downloads">✔</th>
                    <th class="col-comments">💬︎</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($uploads as $upload)
                    @if ($upload->user->trust === null)
                        <tr>
                    @elseif ($upload->user->trust === 0)
                        <tr class="tr--untrusted">
                    @elseif ($upload->user->trust === 1)
                        <tr class="tr--trusted">
                    @endif
                        @auth
                            <td class="col-uploader table-uploaded table-td--center">
                                <a href="{{ auth()->user()->id === $upload->user->id ? route('users.profile') : route('users.show', $upload->user) }}">{{ $upload->user->name }}</a>
                            </td>
                        @else
                            <td class="col-uploader table-uploaded table-td--center">
                                <a href="{{ route('users.show', $upload->user) }}">{{ $upload->user->name }}</a>
                            </td>
                        @endauth
                        <td class="col-category table-cat table-td--center">
                            <a href="{{ "http://localhost/MixTorrents/public/search?filter=0&category=" . $upload->category->id . "&search=" }}">{{ $upload->category->category }}</a>
                        </td>
                        <td class="col-title table-title  data-name">
                            <a href="{{ route('uploads.show', $upload->id) }}">{{ $upload->title ?? $upload->name ??  $upload->filename }}</a>
                        </td>
                        <td class="data-link table-td--center">
                            <span class="dl-arrow">
                                <a href="{{ route('uploads.download', $upload->id) }}">🡇</a>
                            </span>
                            <span class="dl-magnet">
                                <a href="{{ $upload->magnet }}">🧲</a>
                            </span>
                        </td>
                        <td class="col-size table-size table-td--center">{{ $upload->size }}</td>
                        <td class="col-date table-date table-td--center">{{ $upStrdates[$loop->index][0] }}</td>
                        <td class="col-seed table-seeders table-td--center">{{ $upload->seeders }}</td>
                        <td class="col-leec table-leechers table-td--center">{{ $upload->leechers }}</td>
                        <td class="col-down table-downloads table-td--center">{{ $upload->downloads }}</td>
                        <td class="col-comments table-comments table-td--center">
                        @if (!$upload->comments->first())
                            {{ $upload->comments->count() }}</td>
                        @else
                            <a href="{{ route('uploads.show', $upload->id) . '#comments'}}">{{ $upload->comments->count() }}</a></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
{{ $uploads->links('components.pagination') }}
</x-layout>