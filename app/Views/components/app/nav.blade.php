<aside>
    <div class="title">
        <h1>League-Alyze</h1>
    </div>
    <nav>
        <ul>
            <li class="@yield('home_active', '')">
                <a href="{{ route('home.index')->full() }}">
                    <i class="fas fa-grip-horizontal"></i>
                    Home
                </a>
            </li>
            <li class="@yield('matches_active', '')">
                <a href="{{ route('matches.index')->full() }}">
                    <i class="fas fa-history"></i>
                    Matches
                </a>
            </li>
            <li class="@yield('champions_active', '')">
                <a href="">
                    <i class="fas fa-shield-alt"></i>
                    Champions
                </a>
            </li>
        </ul>
    </nav>
</aside>