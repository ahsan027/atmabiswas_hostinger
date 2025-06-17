<?php
include 'backend/Database/db.php';
$database = new Db();
$connection = $database->connect();

$sql = "SELECT * FROM jobs";

$stmt = $connection->prepare($sql);
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career - ATMABISWAS</title>
    <link rel="stylesheet" href="career.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
</head>

<body>
    <header>
        <div class="navbar">
            <div class="logo">
                <img src="https://atmabiswas.org/wp-content/uploads/2024/10/cropped-Monogram-web.webp" alt="Logo">
            </div>
            <ul class="menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="../backend/career/availableJobs.php">Available Jobs</a></li>
                <li><a href="../backend/login/prelogin.php">Login</a></li>
            </ul>
        </div>
    </header>

    <main>
        <main>
            <section class="hero">
                <div class="slider">
                    <img src="../backend/career/picture/picture_7.jpg" alt="Slide 1">

                    <img src="../backend/career/picture/picture_3.jpg" alt="Slide 2" style="display: none;">

                    <img src="../backend/career/picture/picture_5.jpg" alt="Slide 3" style="display: none;">
                    <img src="../backend/career/picture/picture_6.jpg" alt="Slide 4" style="display: none;">
                </div>
                <div class="overlay">
                    <div class="leftside">
                        <h1 class="num"><?php echo count($res) ?></h1>
                        <h1>Available Jobs</h1>
                    </div>
                    <div class="rightSide">
                        <h2>ATMABISWAS Career</h2>
                        <h2><span>Inspiring Excellence</span></h2>
                        <form method="GET" action="../backend/career/searchJobs.php" class="search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" name="searchItem" placeholder="Job Title, Keyword(S)"
                                value="<?php echo isset($_GET['searchItem']) ? htmlspecialchars($_GET['searchItem']) : ''; ?>">
                            <button type="submit">Search</button>
                        </form>
                    </div>


                </div>
                <div class="slider-indicators">
                    <button class="active"></button>
                    <button></button>
                    <button></button>
                    <button></button>
                </div>
            </section>
        </main>

        <section class="Sector">
            <h2>Sector wise Jobs</h2>
            <div class="Sector-list">
                <?php
                $newdb = new Db();
                $newRes = $newdb->connect();

                $sql = "SELECT job_dept, COUNT(*) AS job_count FROM jobs GROUP BY job_dept";
                $stm = $newRes->prepare($sql);
                $stm->execute();
                $finres = $stm->fetchAll(PDO::FETCH_ASSOC);

                
                if (count($finres)>0){
                foreach ($finres as $f) {
                    echo "<a href='../backend/career/sectorWise.php?dept=" . $f['job_dept'] . "'>" . $f['job_dept'] . "<span>" . $f['job_count'] . "</span></a>";
                }

                }else{
                    echo '<p style="
    padding: 15px;
    background-color: #f8f9fa;
    color: #6c757d;
    text-align: center;
    border: 1px dashed #ccc;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    margin: 20px auto;
    max-width: 400px;
">
    ❌ No Job Sector is avaiable Currently
</p>';

                }


                ?>
            </div>
        </section>

        <section class="available-jobs">
    <h2>Available Jobs</h2>
    <div class="job-list">
        <?php
        if (empty($res)) {
            echo '<p style="
                padding: 15px;
                background-color: #f8f9fa;
                color: #6c757d;
                text-align: center;
                border: 1px dashed #ccc;
                border-radius: 6px;
                font-size: 16px;
                font-weight: 500;
                margin: 20px auto;
                max-width: 400px;
            ">
                ❌ No Available Jobs.
            </p>';
        } else {
            foreach ($res as $r) {
                $endDate = new DateTime($r['deadline']);
                $currentDate = new DateTime();

                $interval = $currentDate->diff($endDate);
                $remainingDates = $interval->days;

                if ($endDate > $currentDate) {
                    echo "<a href='../backend/career/jobdes.php?id=" . htmlspecialchars($r['job_id']) . "&deptCode=" . htmlspecialchars($r['job_code']) . "'><div class='job-card'>
                        <h3>" . $r['job_title'] . "</h3>
                        <p>Job id: " . $r["job_id"] . "</p>
                        <p>Department: " . $r['job_dept'] . "</p>
                        <p>Salary: " . $r['salary_range'] . "</p>
                        <p>Experience: " . $r['job_experience'] . "</p>
                        <span class='job-status-text'>" . $remainingDates . " Day Remaining</span>";
                } else {
                    echo "<a href='#' style='color: gray;'><div class='job-card'>
                        <h3>" . $r['job_title'] . "</h3>
                        <p>Job id: " . $r["job_id"] . "</p>
                        <p>Department: " . $r['job_dept'] . "</p>
                        <p>Salary: " . $r['salary_range'] . "</p>
                        <p>Experience: " . $r['job_experience'] . "</p>
                        <span class='job-status-text'>Application Time ended</span>";
                }
                echo "</div></a>";
            }
        }
        ?>
    </div>
</section>

    </main>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slider img');
        const indicators = document.querySelectorAll('.slider-indicators button');

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.display = i === index ? 'block' : 'none';
            });

            indicators.forEach((indicator, i) => {
                indicator.classList.toggle('active', i === index);
            });
        }

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentSlide = index;
                showSlide(currentSlide);
            });
        });

        setInterval(() => {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }, 5000);
    </script>
</body>

</html>