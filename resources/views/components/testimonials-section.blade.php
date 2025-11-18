@props(['testimonials' => [], 'title' => 'What people are saying'])

<section class="testimonials">
    <div class="container">
        <h2 class="section-title">{{ $title }}</h2>
        
        <div class="testimonials-grid">
            @if(count($testimonials) > 0)
                @foreach($testimonials as $testimonial)
                <blockquote class="testimonial">
                    <p class="testimonial__quote">
                        {{ $testimonial['quote'] ?? 'Great testimonial quote here.' }}
                    </p>
                    <footer class="testimonial__author">
                        <cite class="testimonial__name">{{ $testimonial['name'] ?? 'Customer Name' }}</cite>
                        <p class="testimonial__role">{{ $testimonial['role'] ?? 'Job Title at Company' }}</p>
                    </footer>
                </blockquote>
                @endforeach
            @else
                <!-- Default testimonials if none provided -->
                <blockquote class="testimonial">
                    <p class="testimonial__quote">
                        HirePlans helped me land my dream role in under two weeks. The platform is intuitive and the job quality is exceptional.
                    </p>
                    <footer class="testimonial__author">
                        <cite class="testimonial__name">Sarah Chen</cite>
                        <p class="testimonial__role">Product Designer at TechCorp</p>
                    </footer>
                </blockquote>

                <blockquote class="testimonial">
                    <p class="testimonial__quote">
                        As an employer, we've hired three outstanding engineers through HirePlans. The candidate quality is unmatched.
                    </p>
                    <footer class="testimonial__author">
                        <cite class="testimonial__name">Michael Torres</cite>
                        <p class="testimonial__role">CTO at StartupLabs</p>
                    </footer>
                </blockquote>

                <blockquote class="testimonial">
                    <p class="testimonial__quote">
                        The best job platform I've used. Clean interface, relevant opportunities, and responsive support team.
                    </p>
                    <footer class="testimonial__author">
                        <cite class="testimonial__name">Emily Watson</cite>
                        <p class="testimonial__role">Marketing Manager at Global Brands</p>
                    </footer>
                </blockquote>
            @endif
        </div>
    </div>
</section>
