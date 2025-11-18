@props(['logoPath' => '/images/HirePlansLogo.png'])

<header class="header" role="banner">
    <div class="container">
        <nav class="nav" aria-label="Main navigation">
            <div class="nav__logo">
                <a href="/" aria-label="HirePlans home - Better Jobs · Higher Plans">
                    <img src="{{ $logoPath }}" alt="HirePlans" class="logo-img" />
                </a>
            </div>
            
            <input type="checkbox" id="nav-toggle" class="nav__toggle" aria-label="Toggle navigation menu">
            <label for="nav-toggle" class="nav__hamburger" aria-label="Menu">
                <span class="nav__hamburger-line"></span>
                <span class="nav__hamburger-line"></span>
                <span class="nav__hamburger-line"></span>
            </label>
            
            <div class="nav__links">
                <a href="{{ route('jobs.listings') }}" class="nav__link">Jobs</a>
                <a href="{{ route('home') }}#employers" class="nav__link">Employers</a>
                <a href="{{ route('home') }}#about" class="nav__link">About</a>
                <a href="{{ route('home') }}#contact" class="nav__link">Contact</a>
                @guest
                    <a href="{{ route('login') }}" class="btn btn--ghost">Sign in</a>
                    <a href="{{ route('register') }}" class="btn btn--primary">Sign up</a>
                @else
                    <a href="{{ route('platform.index') }}" class="nav__link">Dashboard</a>
                    <a href="{{ route('platform.profile') }}" class="nav__link">Profile</a>
                    <form method="POST" action="{{ route('platform.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn--ghost">Logout</button>
                    </form>
                @endguest
            </div>
        </nav>
    </div>
</header>
