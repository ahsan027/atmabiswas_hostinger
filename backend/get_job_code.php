<?php

include "Database/db.php";


$database = new Db();

$conn = $database->connect();

$jobtitle = htmlspecialchars($_GET["job_title"]);

// $job_title = "Full Stack Developer";

$sql = "SELECT JobCode FROM jobcodes WHERE JobTitle = :job_title";

$stmt = $conn->prepare($sql);

$stmt->bindParam(":job_title", $jobtitle);

$stmt->execute();

$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($res);
// print_r($res);