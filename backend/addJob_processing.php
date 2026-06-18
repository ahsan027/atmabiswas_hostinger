<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: /backend/login/loging.php");
    exit();
}

include "Database/db.php";

$database = new Db();
$conn     = $database->connect();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /backend/DashBoard/addJobPosition.php");
    exit();
}

$jobTitle = trim($_POST["jobtitle"] ?? '');
$jobSec   = trim($_POST["jobsector"] ?? '');

if (empty($jobTitle) && empty($jobSec)) {
    header("Location: /backend/DashBoard/addJobPosition.php?error=empty");
    exit();
}

// Auto-generate a job code: initials of each word + 2-digit random number
// e.g. "Senior Manager" → "SM47", "Field Officer" → "FO13"
$words    = preg_split('/\s+/', strtoupper(trim($jobTitle)));
$initials = '';
foreach ($words as $word) {
    if ($word !== '') $initials .= $word[0];
}
$jobCode = substr($initials, 0, 4) . rand(10, 99);

try {
    if (!empty($jobTitle)) {
        $stmt = $conn->prepare(
            "INSERT INTO jobcodes (JobTitle, JobCode) VALUES (:job_title, :job_code)"
        );
        $stmt->bindParam(":job_title", $jobTitle);
        $stmt->bindParam(":job_code",  $jobCode);
        $stmt->execute();
    }

    if (!empty($jobSec)) {
        $stmtSec = $conn->prepare(
            "INSERT INTO sectors (sector_name) VALUES (:sector)"
        );
        $stmtSec->bindParam(":sector", $jobSec);
        $stmtSec->execute();
    }

    header("Location: /backend/DashBoard/dashboard.php");
    exit();

} catch (PDOException $e) {
    error_log("addJob_processing error: " . $e->getMessage());
    header("Location: /backend/DashBoard/error.php?type=addjob");
    exit();
}
