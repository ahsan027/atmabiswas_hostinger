<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="ATMABISWAS">
    <title>ATMABISWAS – Official NGO Bangladesh | আত্মবিশ্বাস | Since 1991</title>
    <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
    <?php include 'seo.php'; ?>

    <!-- Preconnect: start TCP handshake for CDNs before body is parsed -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">

    <!-- LCP preload: hero image must start downloading before the slider markup is parsed -->
    <link rel="preload" as="image" href="toilet/toiletpic1.jpg" fetchpriority="high">

    <!-- All CSS that body PHP-includes would inject — load here so none of it is render-blocking in body -->
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="menutoggle.css">
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="imageSlider.css">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Google Analytics — async so it never blocks rendering -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-EZVV9DWWY7"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-EZVV9DWWY7');
    </script>
</head>

<body>

    <?php include 'Navbar.php' ?>
    <!-- <br> -->
    <?php include 'imageSlider.php' ?>

    <div class="Numbercontainer">
        <div class="numbers">
            <div class="number-card">
                <h2 id="number1">0</h2>
                <p>Employee's</p>
            </div>
            <div class="number-card">
                <div style="display: flex; justify-content: center; align-items: center; gap: 1px;" class="newDivDiv">
                    <h2 id="number2">0</h2><span style="margin-bottom: 10px; color:black; font-size: 2rem;">K</span>
                </div>

                <p>Served Clients</p>
            </div>
            <div class="number-card">
                <h2 id="number3">0</h2>
                <p>Branchs</p>
            </div>
            <div class="number-card">
                <h2 id="number4">0</h2>
                <p>Years of Foundation</p>
            </div>
        </div>
    </div>

    <h1 style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;">ATMABISWAS (আত্মবিশ্বাস) – Official NGO Bangladesh, Empowering Rural Communities Since 1991</h1>
    <div class="sectionalparts">
        <h2>Our Goals</h2>
        <p>Our mission is to work for progressive social transformation with the aim of institutionalizing a society
            that place <strong>harmony, peace, justice and ecological balance together</strong></p>


    </div>


    <div class="sectionalpartsnew">
        <h2>Latest</h2>
        <p><strong>Find the latest news of ATMABISWAS here.</strong></p>
    </div>

    <?php include 'test.php' ?>



    <?php include 'joinwithus.php' ?>
    <?php include 'partners.html' ?>

    <?php include 'footer.php' ?>



    <script src="index.js?v=2" defer></script>
</body>

</html>