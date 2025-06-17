5<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - ATMABISWAS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            color: #333;
            background-color: #f4f4f4;
            overflow-x: hidden;
        }

        

        header {
            text-align: center;
            background: linear-gradient(90deg, #0a58ca, #176cc6);
            color: white;
            padding: 50px 20px;

        }

        header h1 {
            margin: 0;
            font-size: 2.8rem;
            font-weight: 700;
        }

        header p {
            margin: 10px 0;
            font-size: 1.2rem;
            opacity: 0.9;
        }


        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .section {
            margin-bottom: 40px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .section h2 {
            color: #276a7b;
            margin-bottom: 20px;
            position: relative;
            animation: slideInLeft 1.5s ease-in-out;
        }

        .section p, .section ul {
            line-height: 1.6;
            animation: fadeIn 1.5s ease-in-out;
        }

        .values-list, .get-involved-list {
            list-style-type: none;
            padding: 0;
        }

        .values-list li, .get-involved-list li {
            margin: 10px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 5px solid #4CAF50;
            border-radius: 5px;
            animation: fadeInUp 1.5s ease-in-out;
        }

        .image-section {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .image-section img {
            flex: 1 1 100%;
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transform: scale(0.95);
            transition: transform 0.3s ease;
        }

        .image-section img:hover {
            transform: scale(1);
        }

        .image-section p {
            flex: 1 1 100%;
            margin: 0;
        }

        @media (min-width: 768px) {
            .image-section img {
                flex: 1 1 45%;
            }

            .image-section p {
                flex: 1 1 45%;
            }
        }

        .video-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.video-item {
    flex: 1 1 300px;
    max-width: 500px;
    aspect-ratio: 16 / 9;
    overflow: hidden;
    position: relative;
}

.video-item iframe {
    width: 100%;
    height: 100%;
    border: none;
    position: absolute;
    top: 0;
    left: 0;
}


        footer {
            text-align: center;
            padding: 20px;
            background-color: #4c98af;
            color: white;
            animation: fadeInUp 2s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <?php include 'Navbar.php'?>
    <header>
        <h1>Welcome To ATMABISWAS</h1>
        <p class="color" style="color: #FFFF;">Empowering individuals and fostering self-belief</p>

    </header>

    <div class="container">
        <div class="section">
            <h2>About Us</h2>
            <div class="image-section">
            <img src="office_pic/office_pic.jpg" alt="logo" style="width: 25%; float: left;">

                <p>ATMABISWAS is a non-governmental, non-profit, voluntary, and development-focused organization committed to creating meaningful social change and fostering sustainable development. Established in January 1991 under the Department of Social Welfare, ATMABISWAS has dedicated over three decades to empowering communities across Bangladesh. The organization primarily focuses on serving the disadvantaged populations, striving to uplift their living standards and enhance their access to essential resources and opportunities. <br> <br> Since its inception, ATMABISWAS has worked tirelessly to support marginalized individuals and communities, with an initial emphasis on the district of Chuadanga. Through a range of social welfare programs, development projects, and micro-credit initiatives, the organization has impacted thousands of lives, enabling beneficiaries to break the cycle of poverty and build a better future.</p>
            </div>
        </div>

        <div class="section">
            <h2>Our Vision</h2>
            <p>Our vision is to create a society where poverty, inequality, and injustice are eradicated, and ecological balance is maintained. We strive for a world where every individual has the opportunity to thrive and contribute to sustainable development.</p>
        </div>

        <div class="section">
            <h2>Our Mission</h2>
            <ul class="values-list">
                <li><strong>Empowerment:</strong> Providing resources and opportunities for personal and professional growth.</li>
                <li><strong>Support:</strong> Offering guidance and assistance to individuals in need.</li>
                <li><strong>Community:</strong> Creating a supportive network that fosters collaboration and mutual growth.</li>
                <li><strong>Progressive Social Transformation:</strong> Working towards a society that values harmony, peace, justice, and ecological balance.</li>
            </ul>
        </div>

        <div class="section">
            <h2>Our Values</h2>
            <ul class="values-list">
                <li><strong>Integrity:</strong> We uphold the highest standards of integrity in all our actions.</li>
                <li><strong>Respect:</strong> We value each individual and treat everyone with respect and dignity.</li>
                <li><strong>Innovation:</strong> We embrace change and constantly seek new ways to achieve our mission.</li>
            </ul>
        </div>

        <div class="section">
            <h2>Our Team</h2>
            <div class="image-section">
            <img src="office_pic/00000.jpg" alt="with_pksf" style="width: 25%; float: left;">

                <p>Our team consists of dedicated professionals who are passionate about making a difference. We collaborate to create a positive impact and support each other in our mission to empower communities and foster sustainable development. <br> Our team members come from diverse backgrounds, bringing a wealth of experience and expertise to the organization. We are united by our shared commitment to social justice, equality, and sustainable development. Each member of our team plays a crucial role in driving our mission forward, from field workers to administrative staff, project managers, and volunteers. Together, we strive to create a positive and lasting impact on the communities we serve.</p>
            </div>
        </div>

        <div class="section">
            <h2>Get Involved</h2>
            <ul class="get-involved-list">
                <li><strong>Volunteer:</strong> Share your skills and time to make a difference.</li>
                <li><strong>Donate:</strong> Support our initiatives with your generous contributions.</li>
                <li><strong>Partner:</strong> Collaborate with us to create impactful programs and events.</li>
                <li><strong>Career:</strong> Join our team and contribute to our mission professionally.</li>
            </ul>
        </div>

        <div class="video-section">
        <h1 style="text-align: center; color:  #0a58ca;">OUR WORK IN ACTION</h1>
        <br>
        <br>

    <div class="video-grid">
        <div class="video-item">
            <iframe src="https://www.youtube.com/embed/-rmQDVb3s4k" frameborder="5" allowfullscreen></iframe>
        </div>
        <div class="video-item">
            <iframe src="https://www.youtube.com/embed/i0UxCHapj40" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="video-item">
            <iframe src="https://www.youtube.com/embed/6xb-rN_9j24" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="video-item">
            <iframe src="https://www.youtube.com/embed/JS15JTafAv4" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="video-item">
            <iframe src="https://www.youtube.com/embed/eDMq_ispQYI" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="video-item">
            <iframe src="https://www.youtube.com/embed/nxDIwvOqTVg " frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</div>

    </div>

   <?php include 'footer.php'?>
</body>
</html>
