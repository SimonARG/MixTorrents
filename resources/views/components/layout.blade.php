@include('partials.head')

    <body>

        @include('partials.header')

        <div class="body-container">
            {{ $slot }}
        </div>

        @include('partials.footer')

    </body>

</html>