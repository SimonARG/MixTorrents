<header>
    <nav class="header-container flex-v f-al-cent">
        <ul id="topnav" class="topnav fill-height">
            <li class="topnav-item">
                <a class="flex-v f-al-cent" class="logo" href="{{ route('torrents.index') }}">
                    <span class="red">Mix</span><span class="green">Torrents</span>
                </a>
            </li>
            <li class="topnav-item"><a class="flex-v f-al-cent" href="{{ route('uploads.create') }}">Upload</a></li>
            <li class="topnav-item"><a class="flex-v f-al-cent" href="{{ route('help') }}">Help</a></li>
            <li class="topnav-item"><a class="flex-v f-al-cent" href="{{ route('rules') }}">Rules</a></li>
            <li class="topnav-item"><a class="flex-v f-al-cent" href="{{ route('about') }}">About</a></li>

            <form action="{{ route('uploads.search') }}" method="GET" class="search-form">
                <div class="search-container f-al-cent">
                    <x-filter />
                    <x-category />
    
                    <input type="text" class="search-bar" name="search" placeholder="Search..." 
                    @if (request('search'))
                    value="{{ request('search') }}"
                    @endif>
                    
                    <input class="search-btn" type="submit" value="🔎︎">
                </div>
            </form>
            <x-user />
            <x-flash />

            <div class="hamburger f-al-cent">☰</div>

            <div class="topnav-vertical">
                <li class="topnav-item--v"><a class="flex-v f-al-cent" href="{{ route('uploads.create') }}">Upload</a></li>
                <li class="topnav-item--v"><a class="flex-v f-al-cent" href="{{ route('help') }}">Help</a></li>
                <li class="topnav-item--v"><a class="flex-v f-al-cent" href="{{ route('rules') }}">Rules</a></li>
                <li class="topnav-item--v"><a class="flex-v f-al-cent" href="{{ route('about') }}">About</a></li>

                <div class="user-drop--v">
                    <button class="user-btn--v flex-v f-al-cent">
                        <span class="user-icon">👤︎</span>
                        @auth
                            <span class="user-name--v">{{ auth()->user()->name }}</span>
                        @else
                            <span class="user-name">Anonymous</span>
                        @endauth
                        <span class="user-arrow">⏷</span>
                    </button>
                    
                    <div id="user-dropdown" class="user-dropdown--v">
                        @auth
                            <span>👤︎ Logged in as {{ Auth::user()->name }}</span>
                            <hr>
                            <form method="GET" action="{{ route('uploads.search') }}">
                                <input type="hidden" name="field" value="user_id">
                                <input type="hidden" name="search" value="{{ Auth::user()->id }}">
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

                <form action="{{ route('uploads.search') }}" method="GET" class="search-form--v">
                    <div class="search-container--v">
                        <x-filter />
                        <x-category />
        
                        <input type="text" class="search-bar" name="search" placeholder="Search..." 
                        @if (request('search'))
                        value="{{ request('search') }}"
                        @endif>
                        
                        <input class="search-btn" type="submit" value="🔎︎">
                    </div>
                </form>
            </div>
        </ul>
    </nav>
</header>