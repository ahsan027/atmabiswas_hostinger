<?php

include "Database/db.php";

$positions = [];

$database = new Db();

$conn = $database->connect();


$sql = "SELECT JobTitle FROM jobcodes";

$stmt = $conn->prepare($sql);

$stmt->execute();

$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($jobs as $job) {
    $postion[] = $job["JobTitle"];
}


echo json_encode($postion);
// To send data from php to javascript fetch function;