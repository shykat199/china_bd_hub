@if($paginator->hasPages())
    <nav class="page-navigation justify-content-center d-flex" Area-label="page-navigation">
        <ul class="pagination">
            @if($paginator->onFirstPage())
                <li class="page-item">
                    <a class="page-link active" Area-label="Previous">
                        <span Area-hidden="true">«</span>
                    </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" Area-label="Previous">
                        <span Area-hidden="true">«</span>
                    </a>
                </li>
            @endif

            @foreach($paginator->getUrlRange(1,$paginator->lastPage()) as $key => $url)
                @if($paginator->currentPage() == $key)
                    <li class="page-item"><a class="page-link active">{{ $key }}</a></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $key }}</a></li>
                @endif
            @endforeach

            @if($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" Area-label="Next">
                        <span Area-hidden="true">»</span>
                    </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link active" Area-label="Next">
                        <span Area-hidden="true">»</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif
