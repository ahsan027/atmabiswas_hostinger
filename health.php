<?php include 'Navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-EZVV9DWWY7"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-EZVV9DWWY7');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Health and Nutrition - ATMABISWAS</title>
    <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            overflow-x: hidden;
            background: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }

        .video-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        img, iframe {
            max-width: 100%;
            height: auto;
            display: block;
        }

        h1, h2, h3, p, section {
            margin: 0;
        }

        section {
            padding: 20px 0;
        }

        /* Header */
        header {
            text-align: center;
            background: linear-gradient(90deg, #0a58ca, #176cc6);
            color: white;
            padding: 50px 20px;
        }

        header h1 {
            font-size: 2.8rem;
            font-weight: 700;
        }

        header p {
            margin: 10px 0;
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* Container */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }

        /* Card */
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            padding: 40px;
            margin-bottom: 40px;
        }

        .card img {
            width: 100%;
            height: auto;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .card h2,
        .card h3 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 15px;
            color: #0a58ca;
        }

        /* Services */
        .services {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: space-between;
        }

        .service-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            flex: 1 1 calc(33.333% - 30px);
            padding: 30px;
            text-align: center;
        }

        .service-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        /* Two-column layout */
        .two-col {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            align-items: center;
        }

        .two-col > div {
            flex: 1 1 45%;
        }

        .two-col img {
            width: 100%;
            border-radius: 12px;
        }

        /* Video section */
        .video-section {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
            margin-top: 40px;
        }

        .video-card {
            flex: 1 1 45%;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            padding: 20px;
        }

        .video-card iframe {
            width: 100%;
            height: 500px;
            border: none;
            border-radius: 12px;
        }

        /* Gallery */
        .gallery {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .gallery img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 12px;
        }

        .extra-info {
            margin-top: 20px;
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
            text-align: justify;
        }

        @media (max-width: 768px) {
            .video-card iframe {
                width: 100%;
                height: 300px;
            }

            .services,
            .two-col,
            .video-section {
                flex-direction: column;
            }

            .gallery {
                grid-template-columns: repeat(1, 1fr);
            }

            .two-col > div {
                flex: 1 1 100%;
            }

            header h1 {
                font-size: 2.2rem;
            }

            header p {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>

    <header>
        <h1>ATMABISWAS: Empowering Health & Nutrition</h1>
        <p>Championing community wellness through innovative healthcare and nutritional education.</p>
    </header>

    <div class="container">

        <!-- Our Services -->
        <div class="card">
            <h2>Our Services</h2>
            <div class="services">
                <div class="service-card">
                    <img src="toilet/toiletpic1.jpg" loading="lazy" alt="Household Sanitation Facilities">
                    <h3>Household Sanitation Facilities</h3>
                    <p>
                        We construct modern toilets and provide safe sanitation facilities. We also distribute sanitary
                        napkins and offer practical training on proper hygiene.
                    </p>
                </div>
                <div class="service-card">
                    <img src="Health/health_pic2.jpg" loading="lazy" alt="Free Medicine">
                    <h3>Free Medicine</h3>
                    <p>
                        Essential medicines are provided free of charge, ensuring every community member receives timely
                        and quality care.
                    </p>
                </div>
                <div class="service-card">
                    <img src="Health/helath_pic7.jpg" loading="lazy" alt="Awareness Campaign & Training">
                    <h3>Awareness Campaign & Training</h3>
                    <p>
                        Our outreach programs educate villagers on nutrition, hygiene, and the correct usage of sanitary
                        products—empowering them with vital health knowledge.
                    </p>
                </div>
            </div>
        </div>

        <!-- Health Care Campaigning -->
        <div class="card">
            <h2>Health Care Campaigning</h2>
            <div class="two-col">
                <div>
                    <img src="Health/health_pic4.jpg" loading="lazy" alt="Health Care Campaign">
                </div>
                <div>
                    <h3>Supporting Rural Communities During the Pandemic</h3>
                    <p>
                        Throughout the pandemic, our dedicated teams provided essential healthcare services and raised
                        awareness about health protocols. We empowered rural communities to stay safe and healthy.
                    </p>
                </div>
            </div>
        </div>

        <!-- Volunteer Section -->
        <div class="card">
            <h2>Become a Volunteer</h2>
            <div class="two-col">
                <div>
                    <img src="Health/health_pic3.jpg" loading="lazy" alt="Become a Volunteer">
                </div>
                <div>
                    <h3>Make a Lasting Impact</h3>
                    <p>
                        Join our 1-2 week training program to equip yourself with the skills to support rural
                        communities. Your contribution will create a positive, lasting impact.
                    </p>
                </div>
            </div>
        </div>

        <!-- ATMABISWAS Hospital Section -->
        <div class="card">
            <h2>Contribution of ATMABISWAS Hospital</h2>
            <p>
                ATMABISWAS Hospital is a beacon of hope—offering comprehensive healthcare services with modern
                facilities, a dedicated team, and free medical care for rural areas.
            </p>
            <div class="video-section">
                <div class="video-card">
                    <iframe src="https://www.youtube.com/embed/nxDIwvOqTVg?si=1dpKSHrijinmh8_L"
                        allowfullscreen></iframe>
                </div>
            </div>
            <p class="extra-info">
                Our hospital goes beyond treatments by engaging in community outreach, preventive care initiatives, and
                educational seminars. We strive to build a healthier future through compassion, innovation, and
                excellence in healthcare.
            </p>
        </div>

        <!-- Gallery Section -->
        <div class="card">
            <h2>Gallery</h2>
            <div class="gallery">
                <img src="rmpt/rmpt_pic1.jpg" loading="lazy" alt="Gallery Image 1">
                <img src="Health/helath_pic7.jpg" loading="lazy" alt="Gallery Image 2">
                <img src="Wash/wash_pic1.jpg" loading="lazy" alt="Gallery Image 3">
                <img src="toilet/toiletpic1.jpg" loading="lazy" alt="Gallery Image 4">
                <img src="toilet/toiletpic3.jpg" loading="lazy" alt="Gallery Image 5">
                <img src="Awarness/awarness_pic7.jpg" loading="lazy" alt="Gallery Image 6">
            </div>
        </div>

    </div>

    <?php include 'footer.php' ?>

</body>

</html>
