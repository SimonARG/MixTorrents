@include('partials.head')

    <body>

        @include('partials.header')

        <div class="body-container">
            {{ $slot }}
        </div>

        @include('partials.footer')

        <script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/2.1.0/showdown.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ url('../resources/js/scripts.js') }}"></script>

    </body>

</html>