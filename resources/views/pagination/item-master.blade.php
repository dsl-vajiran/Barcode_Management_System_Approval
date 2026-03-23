@if ($paginator->hasPages())
    @php
        $start = $paginator->firstItem() ?? 0;
        $end = $paginator->lastItem() ?? 0;
        $total = $paginator->total();
    @endphp

    <span style="align-self:center; color: rgba(255,255,255,0.7); margin-right: 10px;">
        Showing {{ $start }}-{{ $end }} of {{ $total }}
    </span>

    @if ($paginator->onFirstPage())
        <span class="disabled">&laquo; Prev</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Prev</a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="disabled">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next">Next &raquo;</a>
    @else
        <span class="disabled">Next &raquo;</span>
    @endif
@endif
