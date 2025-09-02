<?php
session_start();
require_once 'config.php';
?>
<link rel="stylesheet" href="navbar.css">
<link rel="stylesheet" href="menutoggle.css">
<link rel="stylesheet" href="sidebar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Desktop Navbar -->
<div class="navbar desktop-only">
    <div class="top-row">
        <div class="logo"><a href="<?php echo HOME_PATH; ?>"><img src="logoBg.png" loading="lazy" alt=""></a></div>
        <div class="bars">
            <a href="<?php echo NOTICE_PATH; ?>">Notice</a>
            <a target="_blank" href="<?php echo CAREER_PATH; ?>">Career</a>
            <a href="<?php echo PRESS_PATH; ?>">Press</a>
            <a href="<?php echo ABOUTUS_PATH; ?>">About Us</a>
        </div>
    </div>
    <div class="bottom-row">
        <a href="<?php echo HOME_PATH; ?>">Who we are</a>
        <div class="dropdown">
            <div class="maindrop">
                <a href="#">Our Team <span class="space"> </span> <i id="arrow" class="fa-solid fa-caret-down"></i></a>
            </div>
            <div class="dropdown-content">
                <a href="<?php echo EVE_PATH; ?>">Executive</a>
                <a href="<?php echo GENERALBODY_PATH; ?>">General Body</a>
                <a href="<?php echo SENIOR_MANAGEMENT_PATH; ?>">Senior Management</a>
                <a href="<?php echo FOUNDER_PATH; ?>">Founder</a>
            </div>
        </div>
        <div class="dropdown">
            <div class="maindrop">
                <a href="#">What we do <span> </span> <i id="arrow" class="fa-solid fa-caret-down"></i></a>
            </div>
            <div class="dropdown-content">
                <a href="<?php echo GREEN_ENERGY_PATH; ?>">Green Energy</a>
                <a href="<?php echo ENTERPRISE_PATH; ?>">Enterprise Development</a>
                <a href="<?php echo AGRICULTURAL_PATH; ?>">Food & Agriculture</a>
                <a href="<?php echo READYTOEAT_PATH; ?>">Ready To Eat</a>
                <a href="<?php echo HEALTH_PATH; ?>">Health & Nutrition</a>
            </div>
        </div>
        <a href="<?php echo EVENTS_PATH; ?>">Events</a>
        <a href="<?php echo SOCIAL_PATH; ?>">Social</a>
        <a href="<?php echo CONTACT_PATH; ?>">Contacts</a>
        <?php
        if (isset($_SESSION['username'])) {
            echo '<a style="border:2px solid #007bff;" href="' . DASHBOARD_PATH . '">DashBoard</a>';
        } else {

            echo '<a style="border:2px solid #007bff;" href="' . LOGIN_PATH . '">Login</a>';
        }
        ?>

    </div>
</div>

<!-- Mobile Header -->
<div class="mobile-header mobile-only">
    <div class="logo"><a href="<?php echo HOME_PATH; ?>"><img src="logoBg.png" loading="lazy" alt=""></a></div>
    <div class="menu-toggle" id="menu-toggleId">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </div>
</div>

<!-- Mobile Sidebar -->
<div class="sidenav">
    <div class="sidelogo">
        <img src="LOGO/Monogram for web only.png" loading="lazy" alt="Logo" class="profile-img">
        <i id="close-btn" class="fa-solid fa-times"></i>
    </div>
    <a href="<?php echo HOME_PATH; ?>"><i class="fa-solid fa-house-user"></i> Who we are</a>
    <div class="sidedrop">
        <div class="mainsidedrop">
            <a href="#"><i class="fa-solid fa-people-group"></i> Our Team <i id="arrow"
                    class="fa-solid fa-caret-down"></i></a>
        </div>
        <div class="sidedropContent">
            <a href="<?php echo EVE_PATH; ?>"><i class="fa-solid fa-user-tie"></i> Executive</a>
            <a href="<?php echo GENERALBODY_PATH; ?>"><i class="fa-solid fa-users"></i> General Body</a>
            <a href="<?php echo SENIOR_MANAGEMENT_PATH; ?>"><i class="fa-solid fa-user-shield"></i> Senior Management</a>
            <a href="<?php echo FOUNDER_PATH; ?>"><i class="fa-solid fa-user"></i> Founder</a>
        </div>
    </div>
    <div class="sidedrop">
        <div class="mainsidedrop">
            <a href="#"><i class="fa-solid fa-clipboard-list"></i> What we do <i id="arrow"
                    class="fa-solid fa-caret-down"></i></a>
        </div>
        <div class="sidedropContent">
            <a href="<?php echo GREEN_ENERGY_PATH; ?>"><i class="fa-solid fa-leaf"></i> Green Energy</a>
            <a href="<?php echo ENTERPRISE_PATH; ?>"><i class="fa-solid fa-building"></i> Enterprise Developement</a>
            <a href="<?php echo AGRICULTURAL_PATH; ?>"><i class="fa-solid fa-seedling"></i> Food & Agriculture</a>
            <a href="<?php echo READYTOEAT_PATH; ?>"><i class="fa-solid fa-pizza-slice"></i> Ready To Eat</a>
            <a href="<?php echo HEALTH_PATH; ?>"><i class="fa-solid fa-stethoscope"></i> Health & Nutrition</a>
        </div>
    </div>
    <div class="sidedrop">
        <div class="mainsidedrop activities-main">
            <a href="#"><i class="fa-solid fa-chart-line"></i> Activities <i id="arrow"
                    class="fa-solid fa-caret-down"></i></a>
        </div>
        <div class="sidedropContent">
            <a href="<?php echo CAREER_PATH; ?>"><i class="fa-solid fa-briefcase"></i> Career</a>
            <a href="<?php echo NOTICE_PATH; ?>"><i class="fa-solid fa-bullhorn"></i> Notice</a>
            <a href="<?php echo PRESS_PATH; ?>"><i class="fa-solid fa-newspaper"></i> Press</a>
            <a href="<?php echo ABOUTUS_PATH; ?>"><i class="fa-solid fa-circle-info"></i> About Us</a>
        </div>
    </div>
    <a href="<?php echo EVENTS_PATH; ?>"><i class="fa-solid fa-calendar-check"></i> Events</a>
    <a href="<?php echo SOCIAL_PATH; ?>"><i class="fa-solid fa-handshake"></i> Social Work</a>
    <a href="<?php echo CONTACT_PATH; ?>"><i class="fa-solid fa-envelope-open-text"></i> Contacts</a>

    <?php
    if (isset($_SESSION['username'])) {
        echo '<a href="' . DASHBOARD_PATH . '"><i class="fa-solid fa-gauge"></i> Dashboard</a>';
    } else {
        echo '<a href="' . LOGIN_PATH . '"><i class="fa-solid fa-right-to-bracket"></i>Login</a>';
    }

    ?>
</div>

<script src="navbar.js"></script>
<script src="menutoggle.js"></script>