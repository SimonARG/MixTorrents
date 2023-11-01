<div id="user-drop" class="user-drop">
    <button class="user-btn flex-v f-al-cent">
        <span class="user-icon">👤︎</span>
        @auth
            <span class="user-name">{{ auth()->user()->name }}</span>
        @else
            <span class="user-name">Anonymous</span>
        @endauth
        <span class="user-arrow">⏷</span>
    </button>
    <div id="user-dropdown" class="user-dropdown">
        @auth
            <span>👤︎ Logged in as {{ Auth::user()->name }}</span>
            <hr>
            <form method="GET" action="{{ route('user.uploads', Auth::user()->name) }}">
                <input type="hidden" name="field" value="name">
                <input type="hidden" name="search" value="{{ Auth::user()->name }}">
                <button>🡹 Uploads</span></button>
            </form>
            <a href="{{ route('users.profile') }}">⛭ Profile</a>
            <form method="POST" action="{{ route('users.logout') }}">
                @csrf
                <button>✖ Logout</button>
            </form>
        @else
            <a href="{{ route('users.login') }}"><span>➥</span> Login</a>
            <a href="{{ route('users.create') }}"><span>✎</span> Register</a>
        @endauth
    </div>
</div>