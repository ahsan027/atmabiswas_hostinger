<?php require_once 'config.php'; ?>
<link rel="stylesheet" href="<?= SITE_ROOT ?>/foot.css">

<footer class="footer">
    <div class="footer-inner">

        <!-- Brand -->
        <div class="footer-col footer-brand">
            <div class="footer-brand-name">ATMABISWAS</div>
            <p class="footer-tagline">Non-Governmental Voluntary Organisation</p>
            <p class="footer-desc">Empowering individuals and fostering self-belief across Bangladesh since 1991. Committed to sustainable social change and community development.</p>
        </div>

        <!-- Important Links -->
        <div class="footer-col">
            <h4>Important Links</h4>
            <ul class="footer-list">
                <li><a href="<?= NOTICE_PATH ?>"><i class="fa-solid fa-chevron-right"></i> Notice</a></li>
                <li><a href="<?= CAREER_PATH ?>"><i class="fa-solid fa-chevron-right"></i> Career</a></li>
                <li><a href="<?= ABOUTUS_PATH ?>"><i class="fa-solid fa-chevron-right"></i> About Us</a></li>
                <li><a href="<?= PRESS_PATH ?>"><i class="fa-solid fa-chevron-right"></i> Press</a></li>
                <li><a href="<?= CONTACT_PATH ?>"><i class="fa-solid fa-chevron-right"></i> Contact</a></li>
                <li><a href="<?= EVENTS_PATH ?>"><i class="fa-solid fa-chevron-right"></i> Events</a></li>
            </ul>
        </div>

        <!-- Our Programs -->
        <div class="footer-col">
            <h4>Our Programs</h4>
            <ul class="footer-list">
                <li><a href="<?= GREEN_ENERGY_PATH ?>"><i class="fa-solid fa-chevron-right"></i> Green Energy</a></li>
                <li><a href="<?= ENTERPRISE_PATH ?>"><i class="fa-solid fa-chevron-right"></i> Enterprise Development</a></li>
                <li><a href="<?= AGRICULTURAL_PATH ?>"><i class="fa-solid fa-chevron-right"></i> Food &amp; Agriculture</a></li>
                <li><a href="<?= READYTOEAT_PATH ?>"><i class="fa-solid fa-chevron-right"></i> Ready To Eat</a></li>
                <li><a href="<?= HEALTH_PATH ?>"><i class="fa-solid fa-chevron-right"></i> Health &amp; Nutrition</a></li>
                <li><a href="<?= SOCIAL_PATH ?>"><i class="fa-solid fa-chevron-right"></i> Social Work</a></li>
            </ul>
        </div>

        <!-- Find Us -->
        <div class="footer-col">
            <h4>Find Us</h4>
            <iframe
                class="footer-map"
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3654.988273234273!2d88.8443!3d23.640591!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fecc6a98f15555%3A0x7237da8c2d53a42d!2sAtmabiswas!5e0!3m2!1sen!2sbd!4v1739124674000!5m2!1sen!2sbd"
                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
            <p class="footer-address">
                <i class="fa-solid fa-location-dot"></i>
                Chuadanga District, Bangladesh
            </p>
        </div>

    </div>

    <!-- Bottom bar -->
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> <a href="<?= HOME_PATH ?>">ATMABISWAS</a>. All rights reserved.</p>
        <div class="footer-social">
            <a class="fb" target="_blank" href="https://www.facebook.com/atmabiswas.chuadanga/" aria-label="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a class="yt" target="_blank" href="https://www.youtube.com/channel/UCeqHBixXXoYfaX1gBOP-zOw" aria-label="YouTube">
                <i class="fab fa-youtube"></i>
            </a>
            <a class="em" target="_blank" href="https://mail.google.com/mail/?view=cm&fs=1&to=atmabiswas_ngo@yahoo.com" aria-label="Email">
                <i class="fas fa-envelope"></i>
            </a>
            <a class="li" target="_blank" href="https://www.linkedin.com/company/atmabiswas/" aria-label="LinkedIn">
                <i class="fab fa-linkedin-in"></i>
            </a>
        </div>
    </div>
</footer>

<!-- Back to top (floating button) -->
<button id="back-to-top" aria-label="Back to top">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<script>
(function () {
    var btn = document.getElementById('back-to-top');
    window.addEventListener('scroll', function () {
        btn.style.display = window.scrollY > 300 ? 'flex' : 'none';
    });
    btn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}());
</script>
