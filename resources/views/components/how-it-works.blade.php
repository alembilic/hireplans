@props(['title' => 'How it works'])

<section class="how-it-works">
    <div class="container">
        <h2 class="section-title">{{ $title }}</h2>
        
        <div class="how-grid">
            <div class="how-column">
                <h3 class="how-column__title">For job seekers</h3>
                <div class="steps">
                    <div class="step">
                        <div class="step__icon">1</div>
                        <div class="step__content">
                            <h4 class="step__title">Create your profile</h4>
                            <p class="step__text">Build a profile that showcases your skills and experience.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step__icon">2</div>
                        <div class="step__content">
                            <h4 class="step__title">Search and apply</h4>
                            <p class="step__text">Browse thousands of opportunities and apply with one click.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step__icon">3</div>
                        <div class="step__content">
                            <h4 class="step__title">Get hired</h4>
                            <p class="step__text">Connect with employers and land your dream role.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="how-column" id="employers">
                <h3 class="how-column__title">For employers</h3>
                <div class="steps">
                    <div class="step">
                        <div class="step__icon">1</div>
                        <div class="step__content">
                            <h4 class="step__title">Post your job</h4>
                            <p class="step__text">Create a compelling job listing in minutes.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step__icon">2</div>
                        <div class="step__content">
                            <h4 class="step__title">Review candidates</h4>
                            <p class="step__text">Access a pool of qualified, vetted professionals.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step__icon">3</div>
                        <div class="step__content">
                            <h4 class="step__title">Hire exceptional talent</h4>
                            <p class="step__text">Find the perfect match for your team.</p>
                        </div>
                    </div>
                </div>
                <a href="#contact" class="btn btn--primary btn--inline">Post a job</a>
            </div>
        </div>
    </div>
</section>
