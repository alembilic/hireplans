<div class="relative">
    <!-- Background Image -->
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/images/graphic_collage_art-2-PNG.png'); z-index: -2;"></div>

    <!-- Main Content -->
    <div class="h-96 xh-auto xh-screen flex flex-col items-center justify-center text-white bg-black bg-opacity-50">
        <h1 class="text-4xl font-bold mb-6">Let's Find Your Next Job</h1>
        <script>
            function handleSearch(event) {
                event.preventDefault(); // Prevent the form from submitting traditionally
                const searchQuery = document.getElementById('searchInput').value;
                window.location.href = `/jobs/listings?search=${encodeURIComponent(searchQuery)}`; // Redirect with query
            }
        </script>

        <form onsubmit="handleSearch(event)">
            <input type="text" id="searchInput" placeholder="Search jobs..." class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md shadow-sm w-96">
            <button type="submit" class="hidden">Search</button> <!-- Hidden submit button for form -->
        </form>
    </div>

    <!-- Additional Content -->
    <div class="bg-white py-12">
        <div class="container max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-yellow-700 mb-4">Our Mission Statement</h2>
            <p class="text-lg mb-6">Our mission is to connect talented individuals with rewarding career opportunities across the globe. We strive to provide a platform that enables job seekers to find their ideal roles and helps employers to discover exceptional talent.</p>
        </div>
    </div>

    <!-- New Section: World Map -->
    <div class="bg-gray-100 py-12">
        <div class="container max-w-7xl mx-auto px-4 justify-center">
            <h2 class="text-3xl text-center font-bold text-slate-600 mb-4">Expand your reach to global opportunities across organisations, educational institutions and companies. Take your mission to the world.<br /></h2>
            <!-- <p class="text-lg text-center mb-6"></p> -->
            <div class="flex justify-center">
                <!-- <img src="/images/map-307442_1280.png" alt="World Map" class="md:h-96 xw-full xh-auto"> -->
                <img src="/images/graphic_collage_art-2-PNG_bottom.png" alt="World Map" class="md:h-96 xw-full xh-auto">
            </div>
        </div>
    </div>

    <!-- New Section: Services or Features -->
    <div class="bg-white py-12">
        <div class="container max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-yellow-700 mb-4">Why Choose Us</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
                    <h3 class="text-xl font-bold mb-2">Global Reach</h3>
                    <p class="text-lg">Our hiring associates will connect you with partners in MENA, Asia, and Africa, providing opportunities across multiple continents. While our primary focus is Saudi Arabia, our network extends beyond.</p>
                </div>
                <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
                    <h3 class="text-xl font-bold mb-2">Top Employers</h3>
                    <p class="text-lg">We partner with leading organizations to secure high-quality placements for our job seekers. Our diverse network offers opportunities to suit a wide range of career aspirations.</p>
                </div>
                <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
                    <h3 class="text-xl font-bold mb-2">Supportive Partnership</h3>
                    <p class="text-lg">With our cooperative approach, we specialize in helping startups and new businesses with all their hiring needs. From creating a salary scale to assisting with visa processes and onboarding your new talent, we’re here to support every step of the way.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- New Section: Testimonials -->
    <!-- <div class="bg-gray-100 py-12">
        <div class="container max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-yellow-700 mb-4">What Our Users Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
                    <p class="text-lg italic">"Finding a teaching job abroad was so easy with this platform. Highly recommend it!"</p>
                    <p class="text-sm font-bold mt-2">- John Doe, English Teacher</p>
                </div>
                <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
                    <p class="text-lg italic">"The support team was amazing and helped me every step of the way."</p>
                    <p class="text-sm font-bold mt-2">- Jane Smith, Science Teacher</p>
                </div>
            </div>
        </div>
    </div> -->
</div>





