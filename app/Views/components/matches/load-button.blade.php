@if($matches->hasMorePages())
    <button id="load" class="button" data-next_page="{{ $matches->currentPage() + 1 }}" data-toggle_loader>
        Load More
    </button>
@else
    <button id="load" class="button disabled" data-next_page="{{ $matches->currentPage() + 1 }}" data-toggle_loader disabled>
        Nothing more to load
    </button>
@endif