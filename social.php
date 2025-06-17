<?php 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Development- ATMABISWAS </title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
   
   
    <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
   <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
        background-color: #e6f2ff;
        overflow-x:hidden ; 
        }


        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .segment {
            margin-bottom: 40px;
        }

        .segment h2 {
            color: #0078d7;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
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

            margin: 0 0 10px;
            font-size: 1.2rem;
            color: #333;
        }

        .card-content p {
            margin: 0;
            color: #555;
            font-size: 0.9rem;
        }


    </style>
</head>
<body>
<?php include 'Navbar.php'?>

    <div class="container">
                <!-- Empowering Women -->
                <div class="segment">
            <h2>Empowering Women </h2>
            <div class="card-grid">
                <div class="card">
                    <img src="Cow/cow_pic1.jpg" alt="Conference Event">
                    <div class="card-content">
                        <h3>Empowering Women </h3>
                        <p>ATMABISWAS supports women by providing livestock for sustainable livelihoods.</p>
                    </div>
                </div>

                <div class="card">
                    <img src="fish&redy/ABS_04.JPG" alt="Conference Event">
                    <div class="card-content">
                        <h3>Empowering Women </h3>
                        <p>Women receive culinary training to enhance their skills and achieve financial independence.</p>
                    </div>
                </div>

                <div class="card">
                    <img src="women_empowerment/women.jpeg" alt="Conference Event">
                    <div class="card-content">
                        <h3>Empowering Women </h3>
                        <p>ATMABISWAS empowers female farmers with free fertilizer and resources for better agricultural productivity.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Scholarship Segment -->
        <div class="segment">
            <h2>ATMABISWAS Daycare</h2>
            <div class="card-grid">
                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="ATMABISWAS Daycare">
                    <div class="card-content">
                        <h3>ATMABISWAS Daycare </h3>
                        <!-- <p>PKSF was a partner in providing the scholarship.</p> -->
                    </div>
                </div>
                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="ATMABISWAS Daycare">
                    <div class="card-content">
                        <h3>ATMABISWAS Daycare</h3>
                        <!-- <p>Empowering students through education.</p> -->
                    </div>
                </div>
                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="ATMABISWAS Daycare">
                    <div class="card-content">
                        <h3>ATMABISWAS Daycare</h3>
                        <!-- <p>Providing opportunities for bright minds.</p> -->
                    </div>
                </div>
                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="ATMABISWAS Daycare">
                    <div class="card-content">
                        <h3>ATMABISWAS Daycare</h3>
                        <!-- <p>Providing opportunities for bright minds.</p> -->
                    </div>
                </div>                                

            </div>
        </div>

 
        <!-- ATMABISWAS Pathshala -->
        <div class="segment">
            <h2>ATMABISWAS Pathshala </h2>
            <div class="card-grid">
                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="Women Rights Event">
                    <div class="card-content">
                        <h3>ATMABISWAS Pathshala </h3>
                        <!-- <p>Advocating for equal rights.</p> -->
                    </div>
                </div>
                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="Women Rights Event">
                    <div class="card-content">
                        <h3>ATMABISWAS Pathshala </h3>
                        <!-- <p>We are campaigning in the village for gender equality between women and men.</p> -->
                    </div>
                </div>
                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="Women Rights Event">
                    <div class="card-content">
                        <h3>ATMABISWAS Pathshala </h3>
                        <!-- <p>We are campaigning in the city to promote gender equality between women and men.</p> -->
                    </div>
                </div>
            </div>
        </div>

       <!-- Compassionate Care & Support  -->
       <div class="segment">
            <h2>Compassionate Care & Support </h2>
            <div class="card-grid">
                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Compassionate Care & Support </h3>
                        <!-- <p>Enjoying the spirit of winter together.</p> -->
                    </div>
                </div>
                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Compassionate Care & Support </h3>
                        <!-- <p>Festive fun and togetherness.</p> -->
                    </div>
                </div>

                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="Winter-fest Event">
                    <div class="card-content">
                        <h3>Compassionate Care & Support </h3>
                        <!-- <p>Festive fun and togetherness.</p> -->
                    </div>
                </div>
                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Compassionate Care & Support </h3>
                        <!-- <p>Festive fun and togetherness.</p> -->
                    </div>
                </div>

                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Compassionate Care & Support </h3>
                        <!-- <p>Making winter memories.</p> -->
                    </div>
                </div>
            </div>
        </div>


        </div> 
        <!-- Other Events -->
        <div class="segment">
            <h2>Other Program </h2>
            <div class="card-grid">
                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Supporting Disabilitie</h3>
                        <!-- <p>Enjoying the spirit of winter together.</p> -->
                    </div>
                </div>
                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Supporting Disabilitie</h3>
                        <!-- <p>Festive fun and togetherness.</p> -->
                    </div>
                </div>

                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="Winter-fest Event">
                    <div class="card-content">
                        <h3>Supporting Disabilitie</h3>
                        <!-- <p>Festive fun and togetherness.</p> -->
                    </div>
                </div>
                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Supporting Disabilitie</h3>
                        <!-- <p>Festive fun and togetherness.</p> -->
                    </div>
                </div>

                <div class="card">
                    <img src="LOGO/NGO_logo_monogram.png" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Supporting Disabilitie</h3>
                        <!-- <p>Making winter memories.</p> -->
                    </div>
                </div>
            </div>
        </div>
   
    </div>

<?php include 'footer.php'?>
</body>
</html>
