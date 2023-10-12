<div class="user-drop">
    <button class="user-btn">
        @auth
            <span class="user-icon">👤︎</span>
        @else
            <span class="user-icon">👤︎</span>
        @endauth
        @auth
            <span class="user-name">{{auth()->user()->name}}</span>
        @else
            <span class="user-name">Anonymous</span>
        @endauth
        <span class="user-arrow">⏷</span>
    </button>
    <div id="user-dropdown" class="user-dropdown">
        @auth
            <form method="GET" action="{{ route('uploads.search') }}">
                <input type="hidden" name="field" value="user_id">
                <input type="hidden" name="search" value="{{ Auth::user()->id }}">
                <button><span>🡹</span> Uploads</span></button>
            </form>
            <a href="{{ route('users.profile') }}"><span>⛭</span> Profile</a>
            <form method="POST" action="{{ route('users.logout') }}">
                @csrf
                <button><span>✖</span> Logout</button>
            </form>
        @else
            <a href="{{ route('users.login') }}"><span>➥</span> Login</a>
            <a href="{{ route('users.create') }}"><span>✎</span> Register</a>
        @endauth
    </div>
</div>