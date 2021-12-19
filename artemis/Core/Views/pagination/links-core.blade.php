<ul class="pagination-links">
    <li class="first-page @if( $pagination->onFirstPage() ) disabled @endif">
        <a href="{{ $pagination->url(1) }}">
            First
        </a>
    </li>

    <li class="previous-page @if( $pagination->onFirstPage() ) disabled @endif">
        <a href="{{ $pagination->previousPageUrl() ?? $pagination->url($pagination->currentPage()) }}">
            Previous
        </a>
    </li>

    @foreach( $pages_before as $page )
        <li class="page-number">
            <a href="{{ $page['link'] }}">
                {{ $page['page'] }}
            </a>
        </li>
    @endforeach

    <li class="page-number current-page">
        <a href="{{ $pagination->url($pagination->currentPage()) }}">
            {{ $pagination->currentPage() }}
        </a>
    </li>

    @foreach( $pages_after as $page )
        <li class="page-number">
            <a href="{{ $page['link'] }}">
                {{ $page['page'] }}
            </a>
        </li>
    @endforeach

    <li class="next-page @if( $pagination->currentPage() >= $pagination->lastPage() ) disabled @endif">
        <a href="{{ $pagination->nextPageUrl() ?? $pagination->url($pagination->currentPage()) }}">
            Next
        </a>
    </li>

    <li class="last-page @if( $pagination->currentPage() >= $pagination->lastPage() ) disabled @endif">
        <a href="{{ $pagination->url($pagination->lastPage()) }}">
            Last
        </a>
    </li>
</ul>