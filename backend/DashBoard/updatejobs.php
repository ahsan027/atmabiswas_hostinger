<?php
session_start();
include '../Database/db.php';

if (isset($_SESSION['username'])) {
    $database = new Db();
    $connection = $database->connect();

    $sql = "SELECT * FROM jobs";

    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("Location: ../login/loging.php");
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Jobs - ATMABISWAS</title>
    <link rel="stylesheet" href="css/updatejobs.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../images/logo/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100 overflow-x-hidden">
    <div class="flex h-screen">
        <div class="flex h-screen">
            <?php include 'sidebar.php' ?>

        </div>
        <div class="flex w-screen overflow-y-auto">

            <section class="available-jobs">
                <div class="job-list">
                    <?php
                    foreach ($res as $r) {
                        $endDate = new DateTime($r['deadline']);
                        $currentDate = new DateTime();
                        $interval = $currentDate->diff($endDate);
                        $remainingDates = $interval->days;

                        echo "<div class='job-card'>";

                        // Main content link
                        if ($endDate > $currentDate) {
                            echo "<a href='../career/jobdes.php?id=" . htmlspecialchars($r['job_id']) . "&deptCode=" . htmlspecialchars($r['job_code']) . "'>";
                            echo "<h3 >" . $r['job_title'] . "</h3>";
                        } else {
                            echo "<a href='#' style='color: gray;'>";
                            echo "<h3 style=color:gray;>" . $r['job_title'] . "</h3>";
                        }


                        echo "<p>Job id: " . $r["job_id"] . "</p>
        <p>Department: " . $r['job_dept'] . "</p>
        <p>Salary: " . $r['salary_range'] . "</p>
        <p>Experience: " . $r['job_experience'] . "</p>";

                        if ($endDate > $currentDate) {
                            echo "<span>" . $remainingDates . " Day Remaining</span>";
                        } else {
                            echo "<span>Application Time ended</span>";
                        }
                        echo "</a>";
                        echo "<div class='admin-buttons'>
                            <a href='updatejob.php?id=" . htmlspecialchars($r['job_id']) . "&deptCode=" . htmlspecialchars($r['job_code']) . "' class='update-btn'>Update</a>
                            <a href='deletejob.php?id=" . htmlspecialchars($r['job_id']) . "&deptCode=" . htmlspecialchars($r['job_code']) . "' 
                               onclick=\"return confirm('Are you sure you want to delete the job?')\" 
                               class='delete-btn'>Delete</a>
                        </div>";
                        echo "</div>";
                    }
                    ?>

                </div>
            </section>

        </div>

    </div>


    <script src="js/dashboard.js"></script>
</body>

</html>