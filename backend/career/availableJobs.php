<?php
include '../Database/db.php';

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
    <title>Job Board - ATMABISWAS</title>
    <link rel="stylesheet" href="css/avjobs.css">
    <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
</head>

<body>
    <header>
        <div class="navbar">
            <div class="logo">
                <img src="https://atmabiswas.org/wp-content/uploads/2024/10/cropped-Monogram-web.webp" alt="Logo">
            </div>
            <ul class="menu">
                <li><a href="../../frontend/career.php">Home</a></li>
                <li><a href="availableJobs.php">Available Jobs</a></li>
                <li><a href="../login/prelogin.php">Login</a></li>
            </ul>
        </div>
    </header>

    <div class="container">
        <div class="search-section">
            <input type="text" id="searchInput" placeholder="Search job titles or companies...">
            <select id="locationFilter">
                <option value="">All Locations</option>
                <option value="New York">New York</option>
                <option value="London">London</option>
                <option value="Tokyo">Tokyo</option>
                <option value="Remote">Remote</option>
            </select>
            <button class="clear-filters" onclick="clearFilters()">Clear Filters</button>
        </div>

        <?php
        if (count($res) === 0) {
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
                🚫 No jobs are currently available.
            </p>';
        }

        foreach ($res as $r) {
            echo "<div class='jobs-container' id='jobsContainer'>";
            echo "    <!-- Job listings will be here -->";
            echo "    <div class='job-card'>";
            echo "<a href='jobdes.php?id=" . htmlspecialchars($r['job_id']) . "&deptCode=" . htmlspecialchars($r['job_code']) . "' class='job-title'>" . htmlspecialchars($r['job_title']) . "</a>";
            echo "        <p class='company'>ATMABISWAS</p>";
            echo "        <p class='location'>" . $r['job_location'] . "</p>";
            echo "        <p class='salary'>" . $r['salary_range'] . "</p>";
            echo "        <div class='tags'>";
            echo "            <span class='tag'>Full-time</span>";

            $skills = explode(",", $r['job_skillset']);
            foreach ($skills as $skill) {
                echo " <span class='tag'>" . $skill . "</span>";
            }

            echo "        </div>";
            echo '<a class="apply-btn-a" href="jobdes.php?id=' . htmlspecialchars($r['job_id']) . '&deptCode=' . htmlspecialchars($r['job_code']) . '">View Details</a>';
            echo "    </div>";
            echo "</div>";
        }
        ?>
    </div>

    <script>
    function filterJobs() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const locationFilter = document.getElementById('locationFilter').value;
        const jobCards = document.querySelectorAll('.job-card');

        jobCards.forEach(card => {
            const title = card.querySelector('.job-title').textContent.toLowerCase();
            const company = card.querySelector('.company').textContent.toLowerCase();
            const location = card.querySelector('.location').textContent;

            const matchesSearch = title.includes(searchInput) || company.includes(searchInput);
            const matchesLocation = locationFilter === '' || location === locationFilter;

            card.style.display = matchesSearch && matchesLocation ? 'block' : 'none';
        });
    }

    // Clear all filters
    function clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('locationFilter').value = '';
        filterJobs();
    }

    // Event listeners
    document.getElementById('searchInput').addEventListener('input', filterJobs);
    document.getElementById('locationFilter').addEventListener('change', filterJobs);
    </script>
</body>

</html>
