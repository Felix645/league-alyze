@if($matches->hasMorePages())
    <button id="load" class="button" data-current_page="{{ $matches->currentPage() }}" data-next_page="{{ $matches->currentPage() + 1 }}" data-toggle_loader>
        Load More
    </button>
@else
    <button id="load" class="button disabled" data-next_page="{{ $matches->currentPage() + 1 }}" disabled>
        Nothing more to load
    </button>
@endif