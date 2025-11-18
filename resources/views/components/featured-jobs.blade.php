@props(['jobs' => [], 'title' => 'Featured opportunities'])

<section class="featured-jobs" id="jobs">
    <div class="container">
        <h2 class="section-title">{{ $title }}</h2>

        <div class="jobs-grid">
            @foreach($jobs as $job)
                <article class="job-card">
                    <h3 class="job-card__title">{{ $job['title'] ?? 'Job Title' }}</h3>
                    <p class="job-card__company">{{ $job['company'] ?? 'Company Name' }}</p>
                    <p class="job-card__location">{{ $job['location'] ?? 'Location Not Specified' }}</p>
                    <div class="job-card__tags">
                        @if(isset($job['tags']) && is_array($job['tags']))
                            @foreach($job['tags'] as $tag)
                                @if($tag)
                                    <span class="tag">{{ $tag }}</span>
                                @endif
                            @endforeach
                        @else
                            <span class="tag">Full-time</span>
                            <span class="tag">Remote</span>
                        @endif
                    </div>
                    @if(isset($job['salary']) && $job['salary'])
                        <p class="job-card__salary">{{ $job['salary'] }}</p>
                    @endif
                    <a href="{{ $job['link'] ?? '#' }}" class="job-card__link">View role →</a>
                </article>
            @endforeach
        </div>
    </div>
</section>