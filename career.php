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
    <link rel="canonical" href="https://atmabiswas.org/" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career - ATMABISWAS</title>
    <link rel="stylesheet" href="career.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-EZVV9DWWY7"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-EZVV9DWWY7');
    </script>
</head>

<body>
    <header>
        <div class="navbar">
            <div class="logo">
                <a href="index.php">
                    <img src="LOGO/NGO_logo_monogram.png" loading="lazy" alt="Logo">
                </a>
            </div>
            <ul class="menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="backend/career/availableJobs.php">Available Jobs</a></li>
                <li><a href="backend/login/prelogin.php">Login</a></li>
            </ul>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="slider-container">
                <div class="slider">
                    <div class="slide active" data-slide="0">
                        <img src="backend/career/picture/picture_7.jpg" loading="lazy" alt="Career Opportunities">
                    </div>

                    <div class="slide" data-slide="1">
                        <img src="backend/career/picture/picture_3.jpg" loading="lazy" alt="Professional Growth">
                    </div>

                    <div class="slide" data-slide="2">
                        <img src="backend/career/picture/picture_5.JPG" loading="lazy" alt="Team Collaboration">
                    </div>

                    <div class="slide" data-slide="3">
                        <img src="backend/career/picture/picture_6.jpg" loading="lazy" alt="Innovation">
                    </div>
                </div>
            </div>

            <div class="overlay">
                <div class="leftside">
                    <div class="job-counter">
                        <h1 class="num"><?php echo count($res) ?></h1>
                        <h1>Available Jobs</h1>
                        <div class="counter-animation"></div>
                    </div>
                </div>
                <div class="rightSide">
                    <h2>ATMABISWAS Career</h2>
                    <h2><span>Inspiring Excellence</span></h2>
                    <form method="GET" action="backend/career/searchJobs.php" class="search">
                        <div class="search-wrapper">
                            <i class="fa-solid fa-magnifying-glass search-icon"></i>
                            <input type="text" name="searchItem" placeholder="Job Title, Keyword(S)"
                                value="<?php echo isset($_GET['searchItem']) ? htmlspecialchars($_GET['searchItem']) : ''; ?>">
                            <button type="submit">
                                <i class="fas fa-search"></i>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section class="Sector">
            <div class="section-header">
                <h2><i class="fas fa-briefcase"></i> Sector wise Jobs</h2>
                <p>Explore opportunities across different departments</p>
            </div>
            <div class="Sector-list">
                <?php
                $newdb = new Db();
                $newRes = $newdb->connect();

                $sql = "SELECT job_dept, COUNT(*) AS job_count FROM jobs GROUP BY job_dept";
                $stm = $newRes->prepare($sql);
                $stm->execute();
                $finres = $stm->fetchAll(PDO::FETCH_ASSOC);

                if (count($finres) > 0) {
                    foreach ($finres as $f) {
                        echo "<a href='backend/career/sectorWise.php?dept=" . $f['job_dept'] . "' class='sector-card'>
                                <div class='sector-icon'>
                                    <i class='fas fa-building'></i>
                                </div>
                                <div class='sector-info'>
                                    <h3>" . $f['job_dept'] . "</h3>
                                    <span class='job-count'>" . $f['job_count'] . " positions</span>
                                </div>
                              </a>";
                    }
                } else {
                    echo '<div class="no-sectors">
                            <i class="fas fa-info-circle"></i>
                            <p>No Job Sectors available currently</p>
                          </div>';
                }
                ?>
            </div>
        </section>

        <section class="available-jobs">
            <div class="section-header">
                <h2><i class="fas fa-list-alt"></i> Available Jobs</h2>
                <p>Find your perfect opportunity</p>
            </div>
            <div class="job-list">
                <?php
                if (empty($res)) {
                    echo '<div class="no-jobs">
                            <i class="fas fa-briefcase"></i>
                            <h3>No Available Jobs</h3>
                            <p>Check back later for new opportunities</p>
                          </div>';
                } else {
                    foreach ($res as $r) {
                        $endDate = new DateTime($r['deadline']);
                        $currentDate = new DateTime();

                        $interval = $currentDate->diff($endDate);
                        $remainingDates = $interval->days;

                        if ($endDate > $currentDate) {
                            echo "<a href='backend/career/jobdes.php?id=" . htmlspecialchars($r['job_id']) . "&deptCode=" . htmlspecialchars($r['job_code']) . "' class='job-link'>
                                    <div class='job-card'>
                                        <div class='job-header'>
                                            <h3>" . $r['job_title'] . "</h3>
                                            <span class='job-id'>#" . $r["job_id"] . "</span>
                                        </div>
                                        <div class='job-details'>
                                            <div class='job-info'>
                                                <span><i class='fas fa-building'></i> " . $r['job_dept'] . "</span>
                                                <span><i class='fas fa-money-bill-wave'></i> " . $r['salary_range'] . "</span>
                                                <span><i class='fas fa-clock'></i> " . $r['job_experience'] . "</span>
                                            </div>
                                            <div class='job-status active'>
                                                <i class='fas fa-calendar-check'></i>
                                                <span>" . $remainingDates . " Days Remaining</span>
                                            </div>
                                        </div>
                                        <div class='job-actions'>
                                            <span class='view-details'>View Details <i class='fas fa-arrow-right'></i></span>
                                        </div>
                                    </div>
                                  </a>";
                        } else {
                            echo "<div class='job-card expired'>
                                    <div class='job-header'>
                                        <h3>" . $r['job_title'] . "</h3>
                                        <span class='job-id'>#" . $r["job_id"] . "</span>
                                    </div>
                                    <div class='job-details'>
                                        <div class='job-info'>
                                            <span><i class='fas fa-building'></i> " . $r['job_dept'] . "</span>
                                            <span><i class='fas fa-money-bill-wave'></i> " . $r['salary_range'] . "</span>
                                            <span><i class='fas fa-clock'></i> " . $r['job_experience'] . "</span>
                                        </div>
                                        <div class='job-status expired'>
                                            <i class='fas fa-calendar-times'></i>
                                            <span>Application Closed</span>
                                        </div>
                                    </div>
                                  </div>";
                        }
                    }
                }
                ?>
            </div>
        </section>
    </main>

    <script>
        // Simple working image slider
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const indicators = document.querySelectorAll('.indicator');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');

        // Function to show a specific slide
        function showSlide(index) {
            // Hide all slides
            slides.forEach(slide => {
                slide.style.display = 'none';
                slide.classList.remove('active');
            });

            // Remove active class from all indicators
            indicators.forEach(indicator => {
                indicator.classList.remove('active');
            });

            // Show the current slide
            slides[index].style.display = 'block';
            slides[index].classList.add('active');

            // Activate the current indicator
            indicators[index].classList.add('active');
        }

        // Function to go to next slide
        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        // Function to go to previous slide
        function previousSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }

        // Add event listeners when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Show first slide initially
            showSlide(0);

            // Add click events to indicators
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    currentSlide = index;
                    showSlide(currentSlide);
                });
            });

            // Add click events to navigation buttons
            if (prevBtn) {
                prevBtn.addEventListener('click', previousSlide);
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', nextSlide);
            }

            // Auto-play functionality
            setInterval(nextSlide, 5000);

            // Add counter animation
            const counter = document.querySelector('.num');
            if (counter) {
                const finalValue = parseInt(counter.textContent);
                let currentValue = 0;

                const animateCounter = () => {
                    if (currentValue < finalValue) {
                        currentValue += Math.ceil(finalValue / 50);
                        if (currentValue > finalValue) currentValue = finalValue;
                        counter.textContent = currentValue;
                        requestAnimationFrame(animateCounter);
                    }
                };

                // Start counter animation when element is in view
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            animateCounter();
                            observer.unobserve(entry.target);
                        }
                    });
                });

                observer.observe(counter);
            }
        });
    </script>
</body>

</html>