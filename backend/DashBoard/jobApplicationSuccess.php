<?php
$successType = $_GET['type'] ?? 'Success';
$successMsg =  'Successfully! Submitted Application.';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Success</title>
    <link rel="stylesheet" href="css/success.css">
</head>

<body>
    <div class="success-container">
        <div class="checkmark-circle">
            <div class="background"></div>
            <div class="checkmark"></div>
        </div>
        <h1><?php echo htmlspecialchars($successType); ?></h1>
        <p><?php echo htmlspecialchars($successMsg); ?></p>
        <button onclick="goToDashboard()">Back to Available Jobs</button>
    </div>

    <script src="js/success.js"></script>
</body>

</html>