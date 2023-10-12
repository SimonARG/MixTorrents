<x-layout>
    <div class="content-container">
        <div class="content-panel profile-container">
            <h1 class="page-title">{{ $user->name }}'s profile</h1>
            <div class="user-flex">
                <div>
                    <div class="img-container">
                        <img src="{{ url('storage/avatars/' . $user->pic) }}" alt="Avatar">
                    </div>
                    @auth
                    @if (Auth::user()->hasRole('admin'))
                    <div class="user-controls">
                        <a href="{{ route('users.edit',  $user) }}">EDIT</a>
                        <form id="acc-del" method="POST" action="{{ route('users.destroy',  $user->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="del-btn" type="submit">DELETE</button>
                        </form>
                    </div>
                    @endif
                    @endauth
                </div>
                <div class="user-info">
                    <p><span>Username:</span>{{ $user->name }}</p>
                    <p><span>Created on:</span>{{ $strdate }}</p>
                    <p><span>User attributes:</span>
                        @if ($user->roles->first() && $user->trust >= '0')
                        {{ $user->getRole() . ', ' . $user->getTrust() }}
                        @elseif ($user->roles->first() && empty($user->trust))
                        {{ $user->getRole() }}
                        @endif
                    </p>
                    @if ($user->uploads->first())
                        <form method="GET" action="{{ route('user.uploads', $user->name) }}">
                            <input type="hidden" name="field" value="name">
                            <input type="hidden" name="search" value="{{ $user->name }}">
                            <button class="uploads">Uploads:  {{ $user->uploads->count() }}</button>
                        </form>
                    @else
                        <p><span>Uploads: {{ $user->uploads->count() }}</span></p>
                    @endif
                    @if ($user->comments->first())
                        <p><a href="">Comments: {{ $user->comments->count() }}</a></p>
                    @else
                        <p><span>Comments: {{ $user->comments->count() }}</span></p>
                    @endif
                </div>
            </div>
            <span class="about-title">About:</span>
            <p>{{ $user->about }}</p>
        </div>
    </div>
</x-layout>