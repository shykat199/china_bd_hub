@if($paginator->hasPages())
    <div class="pagination-bar justify-content-center d-flex">
        <ul class="pagination">
            @if($paginator->onFirstPage())
                <li class="page-item active">
                    <a class="page-link" Area-label="Previous">
                        <span Area-hidden="true">«</span>
                    </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" Area-label="Previous" data-stat="{{ $stat }}">
                        <span Area-hidden="true">«</span>
                    </a>
                </li>
            @endif

            @foreach($paginator->getUrlRange(1,$paginator->lastPage()) as $key => $url)
                @if($paginator->currentPage() == $key)
                    <li class="page-item active"><a class="page-link" >{{ $key }}</a></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $url }}" data-stat="{{ $stat }}">{{ $key }}</a></li>
                @endif
            @endforeach

            @if($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" Area-label="Next" data-stat="{{ $stat }}">
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
    </div>
@endif
