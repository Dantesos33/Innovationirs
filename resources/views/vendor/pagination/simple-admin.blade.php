@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Pagination">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="page-link disabled" aria-disabled="true">
                <i class="fa-solid fa-chevron-left" style="font-size:10px;"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-link" rel="prev" aria-label="Previous">
                <i class="fa-solid fa-chevron-left" style="font-size:10px;"></i>
            </a>
        @endif

        {{-- Page Numbers --}}
        @php
            $currentPage = $paginator->currentPage();
            $lastPage = $paginator->lastPage();
            $windowStart = max(1, $currentPage - 2);
            $windowEnd = min($lastPage, $currentPage + 2);
        @endphp

        {{-- First page + ellipsis --}}
        @if ($windowStart > 1)
            <a href="{{ $paginator->url(1) }}" class="page-link">1</a>
            @if ($windowStart > 2)
                <span class="page-link disabled" style="border:none;padding:0 4px;">…</span>
            @endif
        @endif

        {{-- Window pages --}}
        @for ($page = $windowStart; $page <= $windowEnd; $page++)
            @if ($page == $currentPage)
                <span class="page-link active" aria-current="page">{{ $page }}</span>
            @else
                <a href="{{ $paginator->url($page) }}" class="page-link">{{ $page }}</a>
            @endif
        @endfor

        {{-- Last page + ellipsis --}}
        @if ($windowEnd < $lastPage)
            @if ($windowEnd < $lastPage - 1)
                <span class="page-link disabled" style="border:none;padding:0 4px;">…</span>
            @endif
            <a href="{{ $paginator->url($lastPage) }}" class="page-link">{{ $lastPage }}</a>
        @endif

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-link" rel="next" aria-label="Next">
                <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
            </a>
        @else
            <span class="page-link disabled" aria-disabled="true">
                <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
            </span>
        @endif

    </nav>
@endif
