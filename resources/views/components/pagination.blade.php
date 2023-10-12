@if ($paginator->hasPages())
    <div class="pag-container">
        <div class="pagination">
            @if (!$paginator->onFirstPage())
                <a href="{{ $paginator->previousPageUrl() }}">&laquo;</a>
            @endif
            
            @foreach ($elements as $element)
                @if (is_string($element))
                    <a href="javascript:void(0);">{{ $element }}</a>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <a class="active" href="#">{{ $page }}</a>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href={{ $paginator->nextPageUrl() }}>&raquo;</a>
            @endif
        </div>
    </div>
@endif