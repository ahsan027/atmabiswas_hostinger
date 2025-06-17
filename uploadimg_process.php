<?php

include 'Database/db.php';

$db = new Db();

$connection = $db->connect();

$uploadDir = "uploads/images/";

$allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];
$imageSize = 2 * 1024 * 1024;


if (!file_exists($uploadDir)) {

    mkdir($uploadDir, 0755, true);
}

function processFile($imageFile, $allowedTypes, $imageSize, $uploadDir)
{

    $fileType = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $fileType->file($imageFile['tmp_name']);

    if ($imageFile['error'] !== UPLOAD_ERR_OK) {
        echo "<p>Uploading Ran into an Error!</p>";
        exit();
    }

    if ($imageFile['size'] > $imageSize) {
        echo "<p>File is too Large!</p>";
        exit();
    }

    if (!in_array($mimeType, $allowedTypes)) {
        echo "<p>Not the Correct File Format</p>";
        exit();
    }

    $ext = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
    $date = date("Y-m-d");
    $new = "PHOTO_" . explode(" ", $_POST['img_title'])[0] . $date . "_" . random_int(1, 1000) . "." . $ext;

    $target = $uploadDir . $new;

    if (!move_uploaded_file($imageFile['tmp_name'], $target)) {
        echo "<p>Failed to Move Uploaded Image!</p>";
        exit();
    }
    return $target;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {
        $img_title =  htmlspecialchars($_POST["img_title"]) ?? "ATMA BISWAS";
        $img_description =  htmlspecialchars($_POST["img_description"]);

        $img_type = $_POST["imagetype"];


        $imageFile = $_FILES["image_file"];

        $image_path = processFile($imageFile, $allowedTypes, $imageSize, $uploadDir);


        $sql = "INSERT INTO img_upload (img_title,img_description,img_path,img_type) VALUES (:img_title,:img_description,:img_path,:img_type)";

        $stmt = $connection->prepare($sql);

        $stmt->bindParam(":img_title", $img_title);

        $stmt->bindParam(":img_description", $img_description);

        $stmt->bindParam(":img_path", $image_path);

        $stmt->bindParam(":img_type", $img_type);


        $stmt->execute();

        header("Location: backend/DashBoard/success.php?type=upload");
    } catch (Exception $e) {
        header("Location: backend/DashBoard/error.php?type=upload");
    }
}
