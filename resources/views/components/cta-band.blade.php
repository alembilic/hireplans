@props([
    'headline' => 'Hire exceptional talent.',
    'text' => 'Connect with qualified professionals ready to make an impact.',
    'primaryButton' => ['text' => 'Post a job', 'link' => '#contact'],
    'secondaryButton' => ['text' => 'Talk to us', 'link' => '#contact'],
    'showModal' => false
])

<section class="cta-band">
    <div class="container">
        <div class="cta-band__content">
            <h2 class="cta-band__headline">{{ $headline }}</h2>
            <p class="cta-band__text">{{ $text }}</p>
            <div class="cta-band__actions">
                @if($showModal)
                    <button onclick="Livewire.dispatch('openModal')" class="btn btn--primary btn--large" style="border: none; cursor: pointer;">
                        {{ $primaryButton['text'] }}
                    </button>
                @else
                    <a href="{{ $primaryButton['link'] }}" class="btn btn--primary btn--large">
                        {{ $primaryButton['text'] }}
                    </a>
                @endif
                <a href="mailto:{{ env('COMPANY_EMAIL') ?? '' }}" class="btn btn--ghost btn--large d-flex items-center">
                    {{ $secondaryButton['text'] }}
                </a>
            </div>
        </div>
    </div>
</section>
