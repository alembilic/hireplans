<div>
    <!-- Hero Section with Enhanced Search -->
    <x-hero-section 
        title="Find the role that fits your plan." 
        subtitle="Discover opportunities with top companies worldwide. Your next career move starts here."
        slogan="Better Jobs · Higher Plans" 
    />

    <!-- Mission Section -->
    <x-mission-section 
        title="Who We Are"
        :content="[
            'Welcome to HirePlans. We\'re a family-run boutique search firm with over 20 years of experience connecting talented professionals with international opportunities, particularly in the Gulf region.',
            'Inspired by the Saudi Vision 2030 initiative, we specialize in matching talent with international schools, as well as leading engineering and construction companies in Saudi Arabia and beyond.',
            'At HirePlans, we take a personalized, strategic approach. We work closely with clients and candidates to build detailed, long-term relationships, ensuring a perfect match every time.'
        ]"
    />

    <!-- Featured Jobs Section -->
    <x-featured-jobs title="Featured opportunities" :jobs="$featuredJobs" />

    <!-- Browse by Category -->
    <x-categories-section title="Browse by category" />

    <!-- How It Works -->
    <x-how-it-works title="How it works" />

    <!-- Testimonials -->
    <x-testimonials-section title="What people are saying" />

    <!-- CTA Band -->
    <x-cta-band 
        headline="Hire exceptional talent."
        text="Connect with qualified professionals ready to make an impact."
        :primaryButton="['text' => 'Post a job', 'link' => '#contact']"
        :secondaryButton="['text' => 'Talk to us', 'link' => '#contact']"
    />
</div>





