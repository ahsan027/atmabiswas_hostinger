<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Health and Nutrition - ATMABISWAS </title>

    <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Reset default margins and paddings */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .video-container {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%;
        /* Aspect ratio 16:9 */
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

    /* Ensure body takes full width without gaps */
    html,
    body {
        width: 100%;
        overflow-x: hidden;
    }

    /* Remove unwanted margin from specific sections */
    .container,
    .services,
    .video-section {
        margin: 0 auto;
        max-width: 100%;
        padding-left: 0;
        padding-right: 0;
    }

    /* Ensure images and videos fit within their containers */
    img,
    iframe {
        max-width: 100%;
        height: auto;
        display: block;
    }

    /* Flexbox fix for unwanted spacing */
    .flex-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }

    /* Remove margin from headings or sections causing gaps */
    h1,
    h2,
    h3,
    p,
    section {
        margin: 0;
    }

    /* Fix header spacing issue */
    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
    }

    /* Fix spacing around buttons, menus, and cards */
    .button,
    .menu,
    .card {
        margin: 0 auto;
    }

    /* Fix any extra spacing in sections */
    section {
        padding: 20px 0;
    }

    body {

        background: #f5f7fa;
        color: #333;
        line-height: 1.6;
        overflow-x: hidden;
    }

    /* Header Styling */
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

    /* Card Styling */
    .card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        padding: 40px;
        margin-bottom: 40px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
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

    /* Services Section */
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
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
    }

    .service-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 20px;
    }


    .two-col {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        align-items: center;
    }

    .two-col>div {
        flex: 1 1 45%;
    }

    .two-col img {
        width: 100%;
        border-radius: 12px;
    }

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
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .video-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
    }

    .video-card iframe {
        width: 100%;
        height: 500px;
        border: none;
        border-radius: 12px;
    }

    /* Gallery Section */
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
        transition: transform 0.3s ease;
    }

    .gallery img:hover {
        transform: scale(1.05);
    }

    /* Extra Info Paragraph */
    .extra-info {
        margin-top: 20px;
        font-size: 1rem;
        color: #555;
        line-height: 1.6;
        text-align: justify;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {

        .video-card iframe {
            width: 100%;
            height: 300px;
            border: none;
            border-radius: 12px;
        }

        .services,
        .two-col,
        .video-section {
            flex-direction: column;
        }

        .gallery {

            grid-template-columns: repeat(1, 1fr);

        }

        .two-col>div {
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
    <?php include 'Navbar.php' ?>

    <header data-aos="fade-down" data-aos-duration="1000">
        <h1>ATMABISWAS: Empowering Health & Nutrition</h1>
        <p>Championing community wellness through innovative healthcare and nutritional education.</p>
    </header>


    <div class="container">

        <div class="card" data-aos="fade-up" data-aos-duration="1000">
            <h2>Our Services</h2>
            <div class="services">
                <!-- Service Card 1: Household Sanitation Facilities -->
                <div class="service-card" data-aos="zoom-in" data-aos-duration="800">
                    <img src="toilet/toiletpic1.jpeg" alt="Household Sanitation Facilities">
                    <h3>Household Sanitation Facilities</h3>
                    <p>
                        We construct modern toilets and provide safe sanitation facilities. We also distribute sanitary
                        napkins and offer practical training on proper hygiene.
                    </p>
                </div>
                <!-- Service Card 2: Free Medicine -->
                <div class="service-card" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="200">
                    <img src="Health/health_pic2.jpeg" alt="Free Medicine">
                    <h3>Free Medicine</h3>
                    <p>
                        Essential medicines are provided free of charge, ensuring every community member receives timely
                        and quality care.
                    </p>
                </div>
                <!-- Service Card 3: Awareness Campaign & Training -->
                <div class="service-card" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="400">
                    <img src="Health/health_pic1.jpeg" alt="Awareness Campaign & Training">
                    <h3>Awareness Campaign & Training</h3>
                    <p>
                        Our outreach programs educate villagers on nutrition, hygiene, and the correct usage of sanitary
                        products—empowering them with vital health knowledge.
                    </p>
                </div>
            </div>
        </div>

        <!-- Health Care Campaigning Section -->
        <div class="card" data-aos="fade-right" data-aos-duration="1000">
            <h2>Health Care Campaigning</h2>
            <div class="two-col">
                <div data-aos="fade-right" data-aos-duration="800">
                    <img src="Health/health_pic4.jpeg" alt="Health Care Campaign">
                </div>
                <div data-aos="fade-left" data-aos-duration="800">
                    <h3>Supporting Rural Communities During the Pandemic</h3>
                    <p>
                        Throughout the pandemic, our dedicated teams provided essential healthcare services and raised
                        awareness about health protocols. We empowered rural communities to stay safe and healthy.
                    </p>
                </div>
            </div>
        </div>

        <!-- Volunteer Section -->
        <div class="card" data-aos="fade-left" data-aos-duration="1000">
            <h2>Become a Volunteer</h2>
            <div class="two-col">
                <div data-aos="fade-right" data-aos-duration="800">
                    <img src="Health/health_pic5.jpg" alt="Become a Volunteer">
                </div>
                <div data-aos="fade-left" data-aos-duration="800">
                    <h3>Make a Lasting Impact</h3>
                    <p>
                        Join our 1-2 week training program to equip yourself with the skills to support rural
                        communities. Your contribution will create a positive, lasting impact.
                    </p>
                </div>
            </div>
        </div>

        <!-- ATMABISWAS Hospital Section -->
        <div class="card" data-aos="fade-up" data-aos-duration="1000">
            <h2>Contribution of ATMABISWAS Hospital</h2>
            <p>
                ATMABISWAS Hospital is a beacon of hope—offering comprehensive healthcare services with modern
                facilities, a dedicated team, and free medical care for rural areas.
            </p>
            <div class="video-section">
                <div class="video-card video-responsive" data-aos="zoom-in" data-aos-duration="800">
                    <iframe src="https://www.youtube.com/embed/nxDIwvOqTVg?si=1dpKSHrijinmh8_L"
                        allowfullscreen></iframe>
                </div>
            </div>

            <!-- Extra Information Paragraph -->
            <p class="extra-info">
                Our hospital goes beyond treatments by engaging in community outreach, preventive care initiatives, and
                educational seminars. We strive to build a healthier future through compassion, innovation, and
                excellence in healthcare.
            </p>
        </div>

        <!-- Gallery Section -->
        <div class="card" data-aos="fade-up" data-aos-duration="1000">
            <h2>Gallery</h2>
            <div class="gallery">
                <img src="rmpt/rmpt_pic1.jpeg" alt="Gallery Image 1">
                <img src="Health/helath_pic7.jpeg" alt="Gallery Image 2">
                <img src="wash/wash_pic1.jpeg" alt="Gallery Image 3">
                <img src="toilet/toiletpic2.jpeg" alt="Gallery Image 4">
                <img src="toilet/toiletpic3.jpeg" alt="Gallery Image 5">
                <img src="awarness/awarness_pic6.jpeg" alt="Gallery Image 6">
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
    AOS.init();
    </script>
</body>

</html>