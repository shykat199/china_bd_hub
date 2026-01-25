@if ($paginator->hasPages())
    <nav class="page-navigation justify-content-center d-flex" Area-label="page-navigation">
        <ul class="pagination" id="pagination">
            @if ($paginator->onFirstPage())
                <li class="page-item">
                    <span class="page-link" Area-label="Previous" Area-hidden="true">«</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" Area-label="Previous">«</a>
                </li>
            @endif

            @php
                $totalPages = $paginator->lastPage();
                $currentPage = $paginator->currentPage();
                $visiblePageCount = 3;
                $halfVisible = floor($visiblePageCount / 2);

                // Determine the start and end of the visible page range
                $start = max($currentPage - $halfVisible, 1);
                $end = min($start + $visiblePageCount - 1, $totalPages);
            @endphp

            @if ($start > 1)
                <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li>
                @if ($start > 2)
                                        <li class="page-item disabled"><span class="page-link" Area-hidden="true">...</span></li>
                @endif
            @endif

            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $currentPage)
                    <li class="page-item active"><span class="page-link active">{{ $i }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                @endif
            @endfor

            @if ($end < $totalPages)
                @if ($end < $totalPages - 1)
                    <li class="page-item disabled"><span class="page-link" Area-hidden="true">...</span></li>
                @endif
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($totalPages) }}">{{ $totalPages }}</a></li>
            @endif

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" Area-label="Next">»</a>
                </li>
            @else
                <li class="page-item">
                    <span class="page-link disabled" Area-label="Next" Area-hidden="false">»</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
