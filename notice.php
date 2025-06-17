<?php
include 'backend/Database/db.php';

$database = new Db();

$conn = $database->connect();

$sql = "SELECT * FROM pdsfiles";

$stmt = $conn->prepare($sql);

$stmt->execute();

$pdfs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice - ATMABISWAS</title>
    <link rel="stylesheet" href="notice.css">
    
    <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
</head>

<body>
    <?php include 'Navbar.php' ?>
    <header>
        <h1>Notice Board – ATMABISWAS</h1>
        <p>
            At ATMABISWAS, we highlight our journey and impact through national and regional media—sharing stories of
            resilience, initiatives that empower communities, and efforts that drive meaningful social change.
        </p>

    </header>

    <div class="container">
        <?php if (count($pdfs) === 0): ?>
            <div class="empty-state">
                <i class="far fa-newspaper"></i>
                <h3>No Notice coverage yet</h3>
                <p>Check back later for updates on our Notice page.</p>
            </div>
        <?php else: ?>

            <div class="grid">
                <?php foreach ($pdfs as $pdf):; ?>

                    <a href="<?php echo $pdf["pdf_path"]; ?>" target="_blank" class="card-link">
                        <embed class="card-embed" src="<?php echo $pdf["pdf_path"]; ?>"></embed>
                        <div class="card-footer">
                            <h2><?php echo $pdf["pdf_title"] ?></h2>
                               <p style="padding: 0.5rem; color: black; border-radius: 15px;">
                                Uploaded at: <?php echo explode(" ", $pdf['upload_date'])[0]; ?>

                            </p>
                            <p style="padding: 0.5rem; background-color: #0073e6; color: white; border-radius: 15px;">
                                <?php echo "View Notice"; ?>
                            </p>

                        </div>
                    </a>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>

    </div>
    <?php include 'footer.php' ?>

</body>

</html>