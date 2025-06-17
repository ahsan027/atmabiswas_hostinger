<?php


include 'Database/db.php';

$db = new Db();
$conn = $db->connect();

$pdf_title = htmlspecialchars($_POST["pdf_title"]);

$uploadDir = "uploads/pdfs/";

$maxSize = 10 * 1024 * 1024;

if (!file_exists($uploadDir)) {

    mkdir($uploadDir, 0755, true);
}

function processPdf($pdfFile, $maxSize, $allowedTypes, $uploadDir)
{

    $fileInfo = new finfo(FILEINFO_MIME_TYPE);
    $mimetype = $fileInfo->file($pdfFile["tmp_name"]);

    if ($pdfFile["error"] !== UPLOAD_ERR_OK) {
        echo "<p>An Error Occurd!</p>";
        exit();
    }

    if (!in_array($mimetype, $allowedTypes)) {
        echo "<p>Invalid File Type</p>";
        exit();
    }

    if ($pdfFile['size'] > $maxSize) {
        echo "<p>File size is too Large</p>";
        exit();
    }

    $ext = pathinfo($pdfFile["name"], PATHINFO_EXTENSION);

    $newFileName = "Notice_" . explode(" ", $_POST['pdf_title'])[0] . "_" . date("Y-m-d") . "_" . random_int(0, 100) . "." . $ext;

    $target = $uploadDir . $newFileName;

    if (!move_uploaded_file($pdfFile["tmp_name"], $target)) {
        echo "<p>Error Occurd While Uploading</p>";
        exit();
    }

    return $target;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {

        $maxSize = 10 * 1024 * 1024;
        $allowedTypes = ["application/pdf"];
        $uploadDir = "uploads/pdfs/";

        $pdfFile = $_FILES["pdf_file"];

        $pdfPath = processPdf($pdfFile, $maxSize, $allowedTypes, $uploadDir);

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $sql = "INSERT INTO pdsFiles (pdf_title,pdf_path) VALUES (:pdf_title,:pdf_path)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(":pdf_title", $pdf_title);
        $stmt->bindParam(":pdf_path", $pdfPath);


        $stmt->execute();
        header("Location: backend/DashBoard/success.php?type=Upload");
    } catch (PDOException $e) {
        header("Location: backend/DashBoard/error.php?type=Upload");
    }
}
