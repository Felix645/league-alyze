<div class="content">
    <section class="top-champions">
        @set($counter = 0)

        @forelse( $top_champions as $champion )
            @include('components.home.champion', ['champion' => $champion])

            @set($counter)
        @empty
            @for($i = 0; $i < 3; $i++)
                @include('components.home.champion-placeholder')
                @set($counter)
            @endfor
        @endforelse

        @if( $top_champions->count() < 3 )
            @for($i = 0; $i < (3 - $counter); $i++)
                @include('components.home.champion-placeholder')
            @endfor
        @endif
    </section>

    <section class="top-roles">
        @foreach($top_roles as $role)
            @include('components.home.role', ['role' => $role])
        @endforeach
    </section>
</div>