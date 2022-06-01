@if($matches->hasMorePages())
    <button id="load" class="button" data-next_page="{{ $matches->currentPage() + 1 }}" data-toggle_loader>
        Load More
    </button>
@else
    <button id="load" class="button disabled" data-next_page="{{ $matches->currentPage() + 1 }}" disabled>
        @if( $first_call )
            No matches found
        @else
            Nothing more to load
        @endif
    </button>
@endif