@if ($paginator->hasPages())
    <nav class="kb-pager" role="navigation" aria-label="Product pagination">
        @if ($paginator->onFirstPage())
            <span class="kb-pager-link kb-pager-link-disabled">
                <i class="bi bi-chevron-left"></i>
                <span class="kb-pager-label">Previous</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="kb-pager-link"
               rel="prev">
                <i class="bi bi-chevron-left"></i>
                <span class="kb-pager-label">Previous</span>
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="kb-pager-ellipsis">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="kb-pager-link kb-pager-link-current">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="kb-pager-link">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="kb-pager-link"
               rel="next">
                <span class="kb-pager-label">Next</span>
                <i class="bi bi-chevron-right"></i>
            </a>
        @else
            <span class="kb-pager-link kb-pager-link-disabled">
                <span class="kb-pager-label">Next</span>
                <i class="bi bi-chevron-right"></i>
            </span>
        @endif
    </nav>
@endif
