<footer class="footer" role="contentinfo">
    <div class="container">
        <div class="footer__grid">
            <div class="footer__column">
                <h3 class="footer__heading">Product</h3>
                <ul class="footer__links">
                    <li><a href="{{ route('jobs.listings') }}">Jobs</a></li>
                    <li><a href="{{ route('home') }}#categories">Categories</a></li>
                    <li><a href="#companies">Companies</a></li>
                </ul>
            </div>
            
            <div class="footer__column">
                <h3 class="footer__heading">Company</h3>
                <ul class="footer__links">
                    <li><a href="{{ route('home') }}#about">About</a></li>
                    <li><a href="#careers">Careers</a></li>
                    <li><a href="#press">Press</a></li>
                </ul>
            </div>
            
            <div class="footer__column">
                <h3 class="footer__heading">Support</h3>
                <ul class="footer__links">
                    <li><a href="{{ route('home') }}#contact" id="contact">Contact</a></li>
                    <li><a href="#faq">FAQ</a></li>
                    <li><a href="{{ route('privacy-policy') }}">Privacy</a></li>
                    <li><a href="{{ route('terms-of-use') }}">Terms</a></li>
                </ul>
            </div>
            
            <div class="footer__column">
                <h3 class="footer__heading">Follow us</h3>
                <div class="social-icons">
                    <a href="#" class="social-icon" aria-label="Twitter">TW</a>
                    <a href="#" class="social-icon" aria-label="LinkedIn">LI</a>
                    <a href="#" class="social-icon" aria-label="GitHub">GH</a>
                </div>
            </div>
        </div>
        
        <div class="footer__bottom">
            <p class="footer__copyright">&copy; {{ date('Y') }} HirePlans. All rights reserved.</p>
        </div>
    </div>
</footer>
