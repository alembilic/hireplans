@props(['categories' => [], 'title' => 'Browse by category'])

<section class="categories">
    <div class="container">
        <h2 class="section-title">{{ $title }}</h2>
        
        <div class="category-grid">
            @if(count($categories) > 0)
                @foreach($categories as $category)
                <a href="{{ $category['link'] ?? '#' }}" class="pill-card">
                    <span class="pill-card__name">{{ $category['name'] ?? 'Category' }}</span>
                    <span class="pill-card__count">{{ $category['count'] ?? '0 jobs' }}</span>
                </a>
                @endforeach
            @else
                <!-- Default categories if none provided -->
                <a href="#" class="pill-card">
                    <span class="pill-card__name">Engineering</span>
                    <span class="pill-card__count">1,247 jobs</span>
                </a>
                <a href="#" class="pill-card">
                    <span class="pill-card__name">Design</span>
                    <span class="pill-card__count">342 jobs</span>
                </a>
                <a href="#" class="pill-card">
                    <span class="pill-card__name">Marketing</span>
                    <span class="pill-card__count">589 jobs</span>
                </a>
                <a href="#" class="pill-card">
                    <span class="pill-card__name">Product</span>
                    <span class="pill-card__count">421 jobs</span>
                </a>
                <a href="#" class="pill-card">
                    <span class="pill-card__name">Data</span>
                    <span class="pill-card__count">673 jobs</span>
                </a>
                <a href="#" class="pill-card">
                    <span class="pill-card__name">Sales</span>
                    <span class="pill-card__count">812 jobs</span>
                </a>
                <a href="#" class="pill-card">
                    <span class="pill-card__name">Operations</span>
                    <span class="pill-card__count">294 jobs</span>
                </a>
                <a href="#" class="pill-card">
                    <span class="pill-card__name">Finance</span>
                    <span class="pill-card__count">456 jobs</span>
                </a>
            @endif
        </div>
    </div>
</section>
