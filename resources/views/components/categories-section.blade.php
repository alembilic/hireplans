@props(['jobTypes' => [], 'locations' => [], 'title' => 'Find Jobs by Type & Location'])

<section class="categories">
    <div class="container">
        <h2 class="section-title">{{ $title }}</h2>
        
        <!-- Job Types Row -->
        <div style="margin-bottom: 2rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #0A0A0A; margin-bottom: 1.25rem;">Job Types</h3>
            <div class="category-grid">
                    @foreach($jobTypes as $type)
                    <a href="{{ $type['link'] ?? '#' }}" class="pill-card">
                        <span class="pill-card__name">{{ $type['name'] ?? 'Type' }}</span>
                        <span class="pill-card__count">{{ $type['count'] ?? '0 jobs' }}</span>
                    </a>
                    @endforeach
            </div>
        </div>

        <!-- Locations Row -->
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #0A0A0A; margin-bottom: 1.25rem;">Top Locations</h3>
            <div class="category-grid">
                    @foreach($locations as $location)
                    <a href="{{ $location['link'] ?? '#' }}" class="pill-card">
                        <span class="pill-card__name">{{ $location['name'] ?? 'Location' }}</span>
                        <span class="pill-card__count">{{ $location['count'] ?? '0 jobs' }}</span>
                    </a>
                    @endforeach
            </div>
        </div>
    </div>
</section>
