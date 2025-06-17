<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ready To Eat - ATMABISWAS </title>
    
    <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {

        background-color: #f3f4f6;
        color: #333;
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
        font-size: 1.8rem;
        font-weight: 700;
    }

    header p {
        margin: 10px 0;
        font-size: 1.2rem;
        opacity: 0.9;
    }

    .center-text {
        text-align: center;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .header {
        text-align: center;
        padding: 50px;
        background: linear-gradient(135deg, #007BFF, #00c6ff);
        color: white;
        border-radius: 15px;
        animation: fadeInDown 1.5s ease;
    }

    .header h1 {
        margin: 0;
        font-size: 3rem;
    }

    .header p {
        margin: 10px 0 0;
        font-size: 1.2rem;
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

    .section {
        margin: 30px 0;
        padding: 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        animation: fadeIn 1.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .section h2 {
        font-size: 2rem;
        text-align: center;
        margin-bottom: 1rem;
        color: #007BFF;
    }

    .section h1 {
        font-size: 2rem;
        text-align: center;
        margin-bottom: 1rem;
        color: #007BFF;
    }

    .section p {
        margin-bottom: 1.2rem;

    }

    .video-container {
        display: flex;
        justify-content: center;
        margin: 20px 0;
        background-color: #007BFF;
    }

    iframe {
        width: 100%;

        height: 450px;
        border: none;
        border-radius: 15px;
    }

    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        animation: fadeInUp 1.5s ease;
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

    .card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    .card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .card-content {
        text-align: center;
        padding: 15px;
    }

    .card-content h3 {
        margin-bottom: 0.5rem;
        color: #007BFF;
    }
    </style>
</head>

<body>
    <?php include 'Navbar.php' ?>
    <header>
        <h1>Ready To Eat - ATMABISWAS</h1>
        <p>In partnership with PKSF, we connect fishermen with local entrepreneurs to ensure access to fresh pond and
            river fish while empowering communities and promoting sustainable livelihoods.</p>
    </header>
    <div class="container">
        <section class="section">
            <h2>Enhancing Resilience Among Fish Farmers and Entrepreneurs</h2>
            <p>ATMABISWAS is committed to empowering communities by providing essential tools, training, and resources
                for sustainable development. Through our renewable energy projects, agricultural support, and
                microfinance initiatives, we’re helping individuals and families create better futures.</p>
            <p>Our collaborations with partners like PKSF enable us to bring practical solutions to fish farmers, small
                business owners, and other vital community members. Discover the impact of our work in building
                resilience and fostering growth across all levels of society.</p>
        </section>

        <section class="section">
            <h2>A glimps of ATMABISWAS RMTP project</h2>
            <div class="video-container">
                <iframe src="https://www.youtube.com/embed/9tw0s0Xm7gE" allowfullscreen></iframe>
            </div>
        </section>

        <style>
        .video-container {
            position: relative;
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
        }
        </style>


        <section class="section">
            <h2 class="center-text">Our Services</h2>
            <div class="card-grid">
                <div class="card">
                    <img src="fish&redy/14.jpg" alt="Training">
                    <div class="card-content">
                        <h3>Training and Empowerment for Fishermen</h3>
                        <p>We provide comprehensive support to fishermen and entrepreneurs, offering tailored training,
                            business guidance, and market development strategies.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="fish&redy/ABS_04.JPG" alt="Entrepreneurship">
                    <div class="card-content">
                        <h3>Building Future Entrepreneurs</h3>
                        <p>We empower new entrepreneurs in the fishery industry by providing hands-on training,
                            mentorship, and business development strategies.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="fish&redy/ABS_06.jpeg" alt="Market Expansion">
                    <div class="card-content">
                        <h3>Market Expansion Opportunities</h3>
                        <p>We support entrepreneurs in creating retail outlets and shops dedicated to selling
                            ready-to-eat fish products.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="center-text">Building Connections, Creating Opportunities</h2>
            <p>Our “Ready to Eat- PKSF Initiative” initiative bridges the gap between fishermen and budding
                entrepreneurs in the fish industry. We offer expert guidance, training, and open doors to new market
                opportunities, creating a sustainable ecosystem where both fishermen and entrepreneurs thrive. Through
                ATMABISWAS, we aim to empower communities with knowledge and the resources needed for success in the B2B
                landscape.</p>
        </section>


        <section class="section">
            <h1>Our Journey: From Training to Market</h1>

            <div class="card-grid">
                <div class="card">
                    <img src="fish&redy/ABS_7.JPG" alt="Training">
                    <div class="card-content">
                        <h3>Training for Fishermen</h3>
                        <p>Our program begins with comprehensive training sessions designed to enhance the skills and
                            knowledge of fishermen.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="fish&redy/BS_10.jpg" alt="Implementation">
                    <div class="card-content">
                        <h3>Fishermen Apply the Training</h3>
                        <p>After completing the training, fishermen implement the techniques learned, focusing on
                            sustainable practices and product quality.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="fish&redy/ABS_01.jpg" alt="Entrepreneurs">
                    <div class="card-content">
                        <h3>Nurturing New Entrepreneurs</h3>
                        <p>We support aspiring entrepreneurs in processing fish products, providing essential resources,
                            mentorship, and business development strategies.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="fish&redy/ABS_07.JPG" alt="Market Opportunities">
                    <div class="card-content">
                        <h3>Sales and Market Opportunities</h3>
                        <p>Finally, we help entrepreneurs create retail channels and connect with new markets to
                            successfully sell their ready-to-eat fish products.</p>
                    </div>
                </div>
            </div>
    </div>
    </section>


    </div>

    <?php include 'footer.php' ?>
</body>

</html>