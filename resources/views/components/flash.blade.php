@if (session()->has('message'))
    <div class="flash-holder">
        <div class="flash-container">
            {{ session('message') }}
        </div>
    </div>
@endif