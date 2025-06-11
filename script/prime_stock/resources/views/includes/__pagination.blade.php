<nav aria-label="Page navigation" class="pagination-style-3 mt-2">
    <ul class="pagination mb-0 flex-wrap">
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <a class="page-link" href="javascript:void(0);">
                    {{ __('Prev') }}
                </a>
            </li>
        @else
            <li class="page-item">
                <a class="page-link text-primary"
                   href="{{ $paginator->previousPageUrl() . (Str::contains($paginator->previousPageUrl(), '?') ? '&' : '?') . http_build_query(request()->except(['page'])) }}">
                    {{ __('Prev') }}
                </a>
            </li>
        @endif
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item"><span class="page-link">{{ $element }}</span></li>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item">
                            <a class="page-link"
                               href="{{ $url . (Str::contains($url, '?') ? '&' : '?') . http_build_query(request()->except(['page'])) }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link text-primary" rel="next"
                   href="{{ $paginator->nextPageUrl() . (Str::contains($paginator->nextPageUrl(), '?') ? '&' : '?') . http_build_query(request()->except(['page'])) }}">
                    {{ __('Next') }}
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <a class="page-link" href="javascript:void(0);">{{ __('Next') }}</a>
            </li>
        @endif
    </ul>
</nav>
