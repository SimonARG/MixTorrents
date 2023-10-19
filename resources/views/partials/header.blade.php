<header>
    <nav class="header-container flex-v f-al-cent">
        <ul class="topnav fill-height">
            <li class="topnav-item">
                <a class="flex-v f-al-cent" class="logo" href="{{ route('torrents.index') }}">
                    <span class="red">Mix</span><span class="green">Torrents</span>
                </a>
            </li>
            <li class="topnav-item"><a class="flex-v f-al-cent" href="{{ route('uploads.create') }}">Upload</a></li>
            <li class="topnav-item"><a class="flex-v f-al-cent" href="{{ route('help') }}">Help</a></li>
            <li class="topnav-item"><a class="flex-v f-al-cent" href="{{ route('rules') }}">Rules</a></li>
            <li class="topnav-item"><a class="flex-v f-al-cent" href="{{ route('about') }}">About</a></li>
        </ul>

        <form action="{{ route('uploads.search') }}" method="GET" class="search-form">
            <div class="search-container flex-v f-al-cent">
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
    </nav>
</header>