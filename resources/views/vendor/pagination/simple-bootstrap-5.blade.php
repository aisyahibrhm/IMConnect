@if ($paginator->hasPages())
    <nav aria-label="Pagination">
        <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="btn btn-ghost btn-sm" style="opacity:0.4; cursor:not-allowed;">
                    <i class="fas fa-chevron-left"></i> Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-ghost btn-sm">
                    <i class="fas fa-chevron-left"></i> Previous
                </a>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span style="color:var(--text-muted); padding:0 4px;">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="btn btn-primary btn-sm"
                                  style="min-width:36px; cursor:default;">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="btn btn-ghost btn-sm"
                               style="min-width:36px;">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-ghost btn-sm">
                    Next <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="btn btn-ghost btn-sm" style="opacity:0.4; cursor:not-allowed;">
                    Next <i class="fas fa-chevron-right"></i>
                </span>
            @endif

            <span style="font-size:12.5px; color:var(--text-muted); margin-left:8px;">
                Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
                of {{ $paginator->total() }} results
            </span>
        </div>
    </nav>
@endif