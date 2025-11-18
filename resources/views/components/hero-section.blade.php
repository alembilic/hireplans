@props(['title' => 'Find the role that fits your plan.', 'subtitle' => 'Discover opportunities with top companies worldwide. Your next career move starts here.', 'slogan' => 'Better Jobs · Higher Plans'])

<section class="hero">
    <div class="container">
        <div class="hero__content">
            <div class="hero__text">
                <p class="hero__slogan">{{ $slogan }}</p>
                <h1 class="hero__headline">{{ $title }}</h1>
                <p class="hero__subtext">{{ $subtitle }}</p>
            </div>
            <div class="hero__illustration">
                <img src="{{ asset('images/undraw_job-hunt_5umi.svg') }}" alt="Person searching for jobs" />
            </div>
        </div>
        
        <form class="search-form" role="search" aria-label="Job search" wire:submit.prevent="searchJobs" id="jobSearchForm">
            <div class="search-form__group">
                <label for="search-role" class="sr-only">Job role or title</label>
                <input 
                    type="text" 
                    id="search-role" 
                    class="search-form__input" 
                    placeholder="Job role or title"
                    aria-label="Job role or title"
                    wire:model="searchQuery"
                >
            </div>
            <div class="search-form__group">
                <label for="search-location" class="sr-only">Location</label>
                <input 
                    type="text" 
                    id="search-location" 
                    class="search-form__input" 
                    placeholder="Location"
                    aria-label="Location"
                    wire:model="location"
                >
            </div>
            <div class="search-form__group">
                <label for="search-type" class="sr-only">Work type</label>
                <select id="search-type" class="search-form__select" aria-label="Work type" wire:model="workType">
                    <option value="">All types</option>
                    <option value="remote">Remote</option>
                    <option value="on-site">On-site</option>
                    <option value="hybrid">Hybrid</option>
                </select>
            </div>
            <button type="submit" class="btn btn--primary btn--large">Search</button>
        </form>

        <div class="trust-bar">
            <div class="trust-stats">
                <div class="trust-stat">
                    <span class="trust-stat__number">20+</span>
                    <span class="trust-stat__label">Years Experience</span>
                </div>
                <div class="trust-stat">
                    <span class="trust-stat__number">Gulf Region</span>
                    <span class="trust-stat__label">Specialist Focus</span>
                </div>
                <div class="trust-stat">
                    <span class="trust-stat__number">Family-Run</span>
                    <span class="trust-stat__label">Boutique Firm</span>
                </div>
                <div class="trust-stat">
                    <span class="trust-stat__number">Vision 2030</span>
                    <span class="trust-stat__label">Inspired Partner</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fallback JavaScript for form submission if Livewire is not available
        document.getElementById('jobSearchForm').addEventListener('submit', function(e) {
            if (typeof Livewire === 'undefined') {
                e.preventDefault();
                const formData = new FormData(this);
                const params = new URLSearchParams();
                
                if (formData.get('search-role')) params.append('search', formData.get('search-role'));
                if (formData.get('search-location')) params.append('location', formData.get('search-location'));
                if (formData.get('search-type')) params.append('type', formData.get('search-type'));
                
                window.location.href = `/jobs/listings?${params.toString()}`;
            }
        });
    </script>
</section>
