<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Page - ATMABISWAS </title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
   
    <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
   <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background-color: #e6f2ff;
        overflow-x: hidden;
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
        <!-- Scholarship Segment -->
        <div class="segment">
            <h2>Scholarship Events</h2>
            <div class="card-grid">
                <div class="card">
                    <img src="Scholarship/pic1.jpeg" alt="Scholarship Event">
                    <div class="card-content">
                        <h3>Scholarship Event </h3>
                        <p>PKSF was a partner in providing the scholarship.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="Scholarship/pic2.jpeg" alt="Scholarship Event">
                    <div class="card-content">
                        <h3>Scholarship Event</h3>
                        <p>Empowering students through education.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="Scholarship/pic3.jpeg" alt="Scholarship Event">
                    <div class="card-content">
                        <h3>Scholarship </h3>
                        <p>Providing opportunities for bright minds.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="Scholarship/pic4.jpeg" alt="Scholarship Event">
                    <div class="card-content">
                        <h3>Scholarship </h3>
                        <p>Providing opportunities for bright minds.</p>
                    </div>
                </div>
                <!-- <div class="card">
                    <img src="https://www.aisct.org/wp-content/uploads/2023/06/1418_0I9A0942_1920px.jpg" alt="Scholarship Event">
                    <div class="card-content">
                        <h3>Education Empowerment</h3>
                        <p>Making dreams come true through education.</p>
                    </div>
                </div> -->
            </div>
        </div>


        <!-- Women Rights Segment -->
        <div class="segment">
            <h2>Women Rights Events </h2>
            <div class="card-grid">
                <div class="card">
                    <img src="women/women_pic1.jpeg" alt="Women Rights Event">
                    <div class="card-content">
                        <h3>Celebrate Women's Day!</h3>
                        <p>Advocating for equal rights.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="women/women_pic2.jpeg" alt="Women Rights Event">
                    <div class="card-content">
                        <h3>Equality Campaign</h3>
                        <p>We are campaigning in the village for gender equality between women and men.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="women/women_pic3.jpeg" alt="Women Rights Event">
                    <div class="card-content">
                        <h3>Equality Campaign</h3>
                        <p>We are campaigning in the city to promote gender equality between women and men.</p>
                    </div>
                </div>
                <!-- <div class="card">
                    <img src="women/women_pic4.jpeg" alt="Women Rights Event">
                    <div class="card-content">
                        <h3>Equal Rights</h3>
                        <p>We are campaigning in the village to promote gender equality between men and women.</p>
                    </div>
                </div>                                -->
                <!-- <div class="card">
                    <img src="https://images.theconversation.com/files/209024/original/file-20180306-146671-haxeo6.jpg" alt="Women Rights Event">
                    <div class="card-content">
                        <h3>Rights Awareness</h3>
                        <p>Championing women's causes.</p>
                    </div>
                </div> -->

                <!-- <div class="card">
                    <img src="https://images.theconversation.com/files/209024/original/file-20180306-146671-haxeo6.jpg" alt="Women Rights Event">
                    <div class="card-content">
                        <h3>Rights Awareness</h3>
                        <p>Championing women's causes.</p>
                    </div>
                </div> -->
            </div>
        </div>




        <!-- Employee Conference Segment -->
        <div class="segment">
            <h2>Employee Conference </h2>
            <div class="card-grid">
                <div class="card">
                    <img src="meeting/meeting_pic2.jpeg" alt="Conference Event">
                    <div class="card-content">
                        <h3>Best Employee Award</h3>
                        <p>We are proud to present the Best Employee Award, recognizing outstanding performance and
                            dedication to excellence.</p>
                    </div>
                </div>

                <div class="card">
                    <img src="exam_hall/exam_hall_pic1.jpeg" alt="Conference Event">
                    <div class="card-content">
                        <h3>Bi-Monthly Meetings with Managers for Guidance and Improvement</h3>
                        <p>We hold bi-monthly meetings with one or two managers to provide guidance on how we can
                            support their branches and discuss strategies for improving their areas.</p>
                    </div>
                </div>

                <div class="card">
                    <img src="meeting/meeting_pic1.jpeg" alt="Conference Event">
                    <div class="card-content">
                        <h3>Annual Conference with Managers at Head Office</h3>
                        <p>Our team conducts an annual conference at the head office, where we meet with each manager to
                            gather feedback on the past year’s progress.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Winterfest Segment -->
        <div class="segment">
            <h2>Winter Events </h2>
            <div class="card-grid">
                <div class="card">
                    <img src="winter/winter_pic1.png" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Winter Fest Celebration</h3>
                        <p>Enjoying the spirit of winter together.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="winter/winter_pic2.png" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Winter Fest Celebration</h3>
                        <p>Enjoying the spirit of winter together.</p>
                    </div>
                </div>

                <div class="card">
                    <img src="winter/winter_pic3.png" alt="Winter-fest Event">
                    <div class="card-content">
                        <h3>Winter Clothes Distribution Program</h3>
                        <p>Festive fun and togetherness.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="winter/winter_pic6.png" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Winter Clothes Distribution Program</h3>
                        <p>Celebration winter together.</p>
                    </div>
                </div>

                <div class="card">
                    <img src="winter/winter_pic5.png" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Winter Clothes Distribution Program</h3>
                        <p>Making winter memories.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Awareness Events Segment -->
        <div class="segment">
            <h2>Awareness Events</h2>
            <div class="card-grid">
                <div class="card">
                    <img src="awarness/awarness_pic1.jpeg" alt="Awareness Event">
                    <div class="card-content">
                        <h3>Disability Awareness Campaign</h3>
                        <p>Spreading Inclusion & Empowerment.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="awarness/awarness_pic2.jpeg" alt="Awareness Event">
                    <div class="card-content">
                        <h3>Anti-Corruption Campaign</h3>
                        <p>Join the fight against corruption. Together, we can build a transparent and just society!</p>
                    </div>
                </div>
                <div class="card">
                    <img src="Health/health_pic3.jpeg" alt="Awareness Event">
                    <div class="card-content">
                        <h3>Health Awareness</h3>
                        <p>Promoting healthy living for a better tomorrow!</p>
                    </div>
                </div>
                <div class="card">
                    <img src="awarness/awarness_pic5.jpeg" alt="Awareness Event">
                    <div class="card-content">
                        <h3>Fire Safety Awareness for Students</h3>
                        <p>Know the steps to stay safe when fire strikes, stay calm, exit quickly and call for help.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="awarness/awarness_pic4.jpeg" alt="Awareness Event">
                    <div class="card-content">
                        <h3>First Aid Awareness for Students</h3>
                        <p>Stay calm and act fast—know how to assist someone who falls sick until help arrives!</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Other Events -->
        <div class="segment">
            <h2>Others Events </h2>
            <div class="card-grid">
                <div class="card">
                    <img src="training/training_pic1.jpeg" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Training</h3>
                        <p>We are providing BD wash training to our employees. </p>
                    </div>
                </div>
                <div class="card">
                    <img src="rmpt/rmpt_pic1.jpeg" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Village BD Rural WASH Camp for HCD Project</h3>
                        <p>We are conducting a rural WASH (Water, Sanitation, and Hygiene) camp in a village in
                            Bangladesh as part of the HCD project.</p>
                    </div>
                </div>

                <div class="card">
                    <img src="rmpt/rmpt_pic2.jpeg" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Free Fertilizer Distribution for Farmers</h3>
                        <p>We are providing free fertilizer to farmers to support agricultural growth and
                            sustainability.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="Fish/fish_pic2.jpeg" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Field Day Celebration to Support Farmers</h3>
                        <p>We celebrated Field Day to educate farmers about sustainable fish farming, crop management,
                            and agricultural best practices.</p>
                    </div>
                </div>

                <div class="card">
                    <img src="Fish/fish_pic1.jpeg" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Celebrating Nutrition Day with Village Communities</h3>
                        <p>We are celebrating Nutrition Day with the village community, promoting healthy eating and
                            well being for all.</p>
                    </div>
                </div>

                <div class="card">
                    <img src="Health/health_pic1.jpeg" alt="Winterfest Event">
                    <div class="card-content">
                        <h3>Free Health Camp for Village Communities</h3>
                        <p>We are organizing a free health camp for village residents, providing essential medical care
                            and health services.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'?>
</body>

</html>