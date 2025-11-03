@if ($paginator->hasPages())
    <nav class="mt-3">
        <ul class="pagination flex-wrap justify-content-center">
            @if (!$paginator->onFirstPage())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->toArray()['first_page_url'] }}" rel="first" aria-label="« First">&lsaquo;&lsaquo;</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->toArray()['last_page_url'] }}" rel="last" aria-label="Last »">&rsaquo;&rsaquo;</a>
                </li>
            @endif
        </ul>
    </nav>
@endif
