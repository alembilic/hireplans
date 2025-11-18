@props(['title' => 'Who We Are', 'content' => []])

<section class="mission" id="about">
    <div class="container">
        <div class="mission__content">
            <h2 class="mission__title">{{ $title }}</h2>
            @if(count($content) > 0)
                @foreach($content as $paragraph)
                    <p class="mission__text">{{ $paragraph }}</p>
                @endforeach
            @else
                <p class="mission__text">
                    Welcome to HirePlans. We're a family-run boutique search firm with over 20 years of experience connecting talented professionals with international opportunities, particularly in the Gulf region.
                </p>
                <p class="mission__text">
                    Inspired by the Saudi Vision 2030 initiative, we specialize in matching talent with international schools, as well as leading engineering and construction companies in Saudi Arabia and beyond.
                </p>
                <p class="mission__text">
                    At HirePlans, we take a personalized, strategic approach. We work closely with clients and candidates to build detailed, long-term relationships, ensuring a perfect match every time.
                </p>
            @endif
        </div>
    </div>
</section>
