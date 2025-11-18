@props([
    'headline' => 'Hire exceptional talent.',
    'text' => 'Connect with qualified professionals ready to make an impact.',
    'primaryButton' => ['text' => 'Post a job', 'link' => '#contact'],
    'secondaryButton' => ['text' => 'Talk to us', 'link' => '#contact']
])

<section class="cta-band">
    <div class="container">
        <div class="cta-band__content">
            <h2 class="cta-band__headline">{{ $headline }}</h2>
            <p class="cta-band__text">{{ $text }}</p>
            <div class="cta-band__actions">
                <a href="{{ $primaryButton['link'] }}" class="btn btn--primary btn--large">
                    {{ $primaryButton['text'] }}
                </a>
                <a href="{{ $secondaryButton['link'] }}" class="btn btn--ghost btn--large">
                    {{ $secondaryButton['text'] }}
                </a>
            </div>
        </div>
    </div>
</section>
