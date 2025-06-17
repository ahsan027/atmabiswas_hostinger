<?php
include '../../Database/db.php';

$db = new Db();
$connection = $db->connect();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {
        $job_code = htmlspecialchars($_POST["job_code"]);
        $job_title = htmlspecialchars($_POST["job_title"]);
        $deadline = htmlspecialchars($_POST["deadline"]);
        $job_dept = $_POST["job_dept"];
        $job_location = htmlspecialchars($_POST["job_location"]);
        $salary_range = htmlspecialchars($_POST["salary_range"]);
        $job_experience = htmlspecialchars($_POST["job_experience"]);
        $job_skillset = $_POST["job_skillset"];
        $job_description = $_POST["job_description"];
        $job_req = $_POST["job_req"];
        $job_benefits = $_POST["job_benefits"];

        $vacancy = htmlspecialchars($_POST["vacancy"]);
        echo $job_dept;

        $sql = "INSERT INTO jobs (job_code, job_title, deadline, job_dept, job_location, salary_range, job_experience, job_skillset, job_description, job_req,job_benefits,vacancy) 
                VALUES (:job_code, :job_title, :deadline, :job_dept, :job_location, :salary_range, :job_experience, :job_skillset, :job_description, :job_req,:job_benefits,:vacancy)";


        $stmt = $connection->prepare($sql);

        $stmt->bindParam(':job_code', $job_code);
        $stmt->bindParam(':job_title', $job_title);
        $stmt->bindParam(':deadline', $deadline);
        $stmt->bindParam(':job_dept', $job_dept);
        $stmt->bindParam(':job_location', $job_location);
        $stmt->bindParam(':salary_range', $salary_range);
        $stmt->bindParam(':job_experience', $job_experience);
        $stmt->bindParam(':job_skillset', $job_skillset);
        $stmt->bindParam(':job_description', $job_description);
        $stmt->bindParam(':job_req', $job_req);
        $stmt->bindParam(":job_benefits", $job_benefits);

        $stmt->bindParam(":vacancy", $vacancy);

        $stmt->execute();
        header("Location: ../dashboard.php");
    } catch (PDOException $err) {
        echo $err;
    }
}