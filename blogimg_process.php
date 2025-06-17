<?php

include 'Database/db.php';

$db = new Db();
$connection = $db->connect();

$uploadDir = "uploads/blog_imgs/";
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
        $coverid = htmlspecialchars($_GET['id']);
        $imgTitle = !empty($_POST['img_title']) ? htmlspecialchars($_POST['img_title']) : null;
        $source = !empty($_POST['blog_source']) ? filter_var($_POST['blog_source'], FILTER_SANITIZE_URL) : null;
        $image_path = null;

        $fields = [];
        $params = [];

        // Process image if uploaded
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $image_path = processFile($_FILES['image_file'], $allowedTypes, $imageSize, $uploadDir);
            $fields[] = "cover_img = :img_path";
            $params[':img_path'] = $image_path;
        }

        // Add title if present
        if ($imgTitle) {
            $fields[] = "image_title = :img_title";
            $params[':img_title'] = $imgTitle;
        }

        // Add source if valid
        if ($source && filter_var($source, FILTER_VALIDATE_URL)) {
            $fields[] = "source_link = :blog_source";
            $params[':blog_source'] = $source;
        }

        // If no fields present, redirect with error
        if (empty($fields)) {
            header("Location: backend/DashBoard/error.php?type=empty");
            exit();
        }

        // Build final SQL and execute
        $sql = "UPDATE blogs SET " . implode(", ", $fields) . " WHERE blog_id = :id";
        $params[':id'] = $coverid;

        $stmt = $connection->prepare($sql);
        $stmt->execute($params);

        header("Location: backend/DashBoard/success.php?type=upload");
        exit();

    } catch (Exception $e) {
        header("Location: backend/DashBoard/error.php?type=upload");
        exit();
    }
}
