<x-layout>
    <div class="content-container">
        <div class="content-panel profile-container">
            <h1 class="page-title">Your profile</h1>
            <div class="user">
                <div>
                    <div class="img-container">
                        <img src="{{url('storage/avatars/'.Auth::user()->pic)}}" alt="Avatar">
                    </div>
                    <div class="user-controls flex-v">
                        <a href="{{ route('users.edit', Auth::user()) }}">EDIT</a>
                        <form id="acc-del" method="POST" action="{{ route('users.destroy', Auth::user()->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="del-btn" type="submit">DELETE</button>
                        </form>
                    </div>
                </div>
                <div class="user-info flex-c f-just-cent">
                    <p><span>Username:</span>{{ Auth::user()->name }}</p>
                    <p><span>Created on:</span>{{ $strdate }}</p>
                    <p><span>User attributes:</span>
                        @if (Auth::user()->roles->first() && Auth::user()->trust >= '0')
                        {{ Auth::user()->getRole() . ', ' . Auth::user()->getTrust() }}
                        @elseif (Auth::user()->roles->first() && empty(Auth::user()->trust))
                        {{ Auth::user()->getRole() }}
                        @endif
                    </p>
                    @if (Auth::user()->uploads->first())
                        <form method="GET" action="{{ route('user.uploads', Auth::user()->name) }}">
                            <input type="hidden" name="field" value="name">
                            <input type="hidden" name="search" value="{{ Auth::user()->name }}">
                            <button class="uploads">Uploads:  {{ Auth::user()->uploads->count() }}</button>
                        </form>
                    @else
                        <p><span>Uploads: {{ Auth::user()->uploads->count() }}</span></p>
                    @endif
                    @if (Auth::user()->comments->first())
                        <p><a href="">Comments: {{ Auth::user()->comments->count() }}</a></p>
                    @else
                        <p><span>Comments: {{ Auth::user()->comments->count() }}</span></p>
                    @endif
                </div>
            </div>
            <span class="about-title">About:</span>
            <p>{{ Auth::user()->about }}</p>
        </div>
    </div>
</x-layout>