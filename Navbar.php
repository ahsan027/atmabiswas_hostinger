<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/config.php';
?>
<link rel="stylesheet" href="<?= SITE_ROOT ?>/navbar.css">
<link rel="stylesheet" href="<?= SITE_ROOT ?>/menutoggle.css">
<link rel="stylesheet" href="<?= SITE_ROOT ?>/sidebar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Desktop Navbar -->
<div class="navbar-band">

    <!-- Top row: white band, logo left, utility links right -->
    <div class="navbar">
        <div class="top-row">
            <div class="logo">
                <a href="<?= HOME_PATH ?>"><img src="<?= SITE_ROOT ?>/logoBg.png" loading="lazy" alt="ATMABISWAS NGO"></a>
            </div>
            <div class="bars">
                <a href="<?= NOTICE_PATH ?>">Notice</a>
                <a href="<?= CAREER_PATH ?>" target="_blank">Career</a>
                <a href="<?= PRESS_PATH ?>">Press</a>
                <a href="<?= ABOUTUS_PATH ?>">About Us</a>
            </div>
        </div>
    </div>

    <!-- Bottom row: full-width cyan band, centered nav links -->
    <div class="bottom-band">
        <div class="navbar">
            <div class="bottom-row">
                <a href="<?= HOME_PATH ?>">Who We Are</a>

                <div class="dropdown">
                    <div class="maindrop">
                        <a href="#">Our Team <i class="fa-solid fa-caret-down arrow-icon"></i></a>
                    </div>
                    <div class="dropdown-content">
                        <a href="<?= EVE_PATH ?>">Executive</a>
                        <a href="<?= GENERALBODY_PATH ?>">General Body</a>
                        <a href="<?= SENIOR_MANAGEMENT_PATH ?>">Senior Management</a>
                        <a href="<?= FOUNDER_PATH ?>">Founder</a>
                    </div>
                </div>

                <div class="dropdown">
                    <div class="maindrop">
                        <a href="#">What We Do <i class="fa-solid fa-caret-down arrow-icon"></i></a>
                    </div>
                    <div class="dropdown-content">
                        <a href="<?= GREEN_ENERGY_PATH ?>">Green Energy</a>
                        <a href="<?= ENTERPRISE_PATH ?>">Enterprise Development</a>
                        <a href="<?= AGRICULTURAL_PATH ?>">Food &amp; Agriculture</a>
                        <a href="<?= READYTOEAT_PATH ?>">Ready To Eat</a>
                        <a href="<?= HEALTH_PATH ?>">Health &amp; Nutrition</a>
                    </div>
                </div>

                <a href="<?= EVENTS_PATH ?>">Events</a>
                <a href="<?= SOCIAL_PATH ?>">Social</a>
                <a href="<?= CONTACT_PATH ?>">Contact</a>

                <?php if (isset($_SESSION['username'])): ?>
                    <a class="nav-login-btn" href="<?= DASHBOARD_PATH ?>">Dashboard</a>
                <?php else: ?>
                    <a class="nav-login-btn" href="<?= LOGIN_PATH ?>">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div><!-- /.navbar-band -->

<!-- Mobile Header -->
<div class="mobile-header">
    <div class="logo">
        <a href="<?= HOME_PATH ?>"><img src="<?= SITE_ROOT ?>/logoBg.png" loading="lazy" alt="ATMABISWAS NGO"></a>
    </div>
    <div class="menu-toggle" id="menu-toggleId">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </div>
</div>

<!-- Mobile Sidebar -->
<div class="sidenav">
    <div class="sidelogo">
        <img src="<?= SITE_ROOT ?>/LOGO/Monogram for web only.png" loading="lazy" alt="Logo" class="profile-img">
        <i id="close-btn" class="fa-solid fa-times"></i>
    </div>

    <a href="<?= HOME_PATH ?>"><i class="fa-solid fa-house-user"></i> Who We Are</a>

    <div class="sidedrop">
        <div class="mainsidedrop">
            <a href="#"><i class="fa-solid fa-people-group"></i> Our Team <i class="fa-solid fa-caret-down side-arrow"></i></a>
        </div>
        <div class="sidedropContent">
            <a href="<?= EVE_PATH ?>"><i class="fa-solid fa-user-tie"></i> Executive</a>
            <a href="<?= GENERALBODY_PATH ?>"><i class="fa-solid fa-users"></i> General Body</a>
            <a href="<?= SENIOR_MANAGEMENT_PATH ?>"><i class="fa-solid fa-user-shield"></i> Senior Management</a>
            <a href="<?= FOUNDER_PATH ?>"><i class="fa-solid fa-user"></i> Founder</a>
        </div>
    </div>

    <div class="sidedrop">
        <div class="mainsidedrop">
            <a href="#"><i class="fa-solid fa-clipboard-list"></i> What We Do <i class="fa-solid fa-caret-down side-arrow"></i></a>
        </div>
        <div class="sidedropContent">
            <a href="<?= GREEN_ENERGY_PATH ?>"><i class="fa-solid fa-leaf"></i> Green Energy</a>
            <a href="<?= ENTERPRISE_PATH ?>"><i class="fa-solid fa-building"></i> Enterprise Development</a>
            <a href="<?= AGRICULTURAL_PATH ?>"><i class="fa-solid fa-seedling"></i> Food &amp; Agriculture</a>
            <a href="<?= READYTOEAT_PATH ?>"><i class="fa-solid fa-pizza-slice"></i> Ready To Eat</a>
            <a href="<?= HEALTH_PATH ?>"><i class="fa-solid fa-stethoscope"></i> Health &amp; Nutrition</a>
        </div>
    </div>

    <div class="sidedrop">
        <div class="mainsidedrop activities-main">
            <a href="#"><i class="fa-solid fa-chart-line"></i> Activities <i class="fa-solid fa-caret-down side-arrow"></i></a>
        </div>
        <div class="sidedropContent">
            <a href="<?= CAREER_PATH ?>"><i class="fa-solid fa-briefcase"></i> Career</a>
            <a href="<?= NOTICE_PATH ?>"><i class="fa-solid fa-bullhorn"></i> Notice</a>
            <a href="<?= PRESS_PATH ?>"><i class="fa-solid fa-newspaper"></i> Press</a>
            <a href="<?= ABOUTUS_PATH ?>"><i class="fa-solid fa-circle-info"></i> About Us</a>
        </div>
    </div>

    <a href="<?= EVENTS_PATH ?>"><i class="fa-solid fa-calendar-check"></i> Events</a>
    <a href="<?= SOCIAL_PATH ?>"><i class="fa-solid fa-handshake"></i> Social Work</a>
    <a href="<?= CONTACT_PATH ?>"><i class="fa-solid fa-envelope-open-text"></i> Contact</a>

    <?php if (isset($_SESSION['username'])): ?>
        <a href="<?= DASHBOARD_PATH ?>"><i class="fa-solid fa-gauge"></i> Dashboard</a>
    <?php else: ?>
        <a href="<?= LOGIN_PATH ?>"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
    <?php endif; ?>
</div>

<script src="<?= SITE_ROOT ?>/navbar.js"></script>
<script src="<?= SITE_ROOT ?>/menutoggle.js"></script>
