<?php


include "Database/db.php";

$database = new Db();

$conn = $database->connect();


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $jobTitle = htmlspecialchars($_POST["jobtitle"]);

    $jobSec = htmlspecialchars($_POST["jobsector"]);

    if (!empty($jobTitle) && !empty($jobSec)) {


        $stmt = $conn->prepare("INSERT INTO jobcodes (JobTitle) VALUES (:job_title)");

        $stmtSec = $conn->prepare("INSERT INTO sectors (sector_name) VALUES (:sector)");


        $stmt->bindParam(":job_title", $jobTitle);
        $stmtSec->bindParam(":sector", $jobSec);

        $stmt->execute();

        $stmtSec->execute();
    } elseif (!empty($jobTitle) && empty($jobSec)) {

        $stmt = $conn->prepare("INSERT INTO jobcodes (JobTitle) VALUES (:job_title)");

        $stmt->bindParam(":job_title", $jobTitle);

        $stmt->execute();
    } elseif (empty($jobTitle) && !empty($jobSec)) {

        $stmtSec = $conn->prepare("INSERT INTO sectors (sector_name) VALUES (:sector)");


        $stmtSec->bindParam(":sector", $jobSec);

        $stmtSec->execute();
    } else {
        echo "Both of the Fields are empty";
        exit();
    }





    header("Location: Dashboard/dashboard.php");
}