<?php
include '../Database/db.php';
session_start();

$database = new Db();
$connection = $database->connect();

$usernameErr = "";
$passErr = "";
$invalid = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($username)) {
        $usernameErr = "Email is Required";
    }

    if (empty($password)) {
        $passErr = "Password is Required";
    }

    if ($username && $password) {

        $sql = "SELECT * FROM admins WHERE email = :username";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['pswd'])) {

            $invalid = "Invalid Credentials";
        } else {

            $_SESSION['username'] = $user['fullname'];
            header("Location: ../DashBoard/dashboard.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATMABISWAS - Login</title>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" type="text/css" href="../Assets/css/login-signup.css">
  
    <link rel="icon" type="image/png" href="../images/logo/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />


    <style>
    .error {
        color: #fff;
        background-color: rgba(255, 0, 0, 0.8);
        width: 40%;
        font-size: 16px;
        margin-top: 5px;
        padding: 10px;
        border-radius: 4px;
        text-align: center;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);

        /* Add transition for smooth movement */
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    /* On hover (for desktop) */
    .error:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    /* On active/touch (mobile-friendly) */
    .error:active {
        transform: scale(0.95);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }
    </style>
</head>

<body>
    <div id="notificationBar" style="
    position: fixed;
    top: -100px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #1e1e1e;
    color: white;
    padding: 15px 25px;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    z-index: 9999;
    font-family: Arial, sans-serif;
    transition: top 0.6s ease-in-out;
    min-width: 280px;
    max-width: 90%;
    text-align: center;
">
    <span>
        📧 Contact <strong>ATMABISWAS IT</strong> via <a href="mailto:support@atmabiswas.org" style="color: #4da6ff; text-decoration: underline;">support@atmabiswas.org</a>
    </span>
    <span onclick="hideNotification()" style="cursor: pointer; margin-left: 15px; font-weight: bold;">&times;</span>
</div>
    <div class="wrapper">
        <div class="box">
            <form action="" method="POST">
                <img src="../images/logo/logo.png">
                <h1>Welcome to, ATMABISWAS Dashboard</h1>
                <div class="username">
                    <input type="text" name="username" placeholder="Email" value="">
                    <i class="fa-regular fa-envelope"></i>
                    <?php
                    if (strlen($usernameErr) !== 0) {
                        echo "<p class='error'>$usernameErr</p>";
                    }
                    ?>
                </div>
                <div class="password">
                    <input id="password" type="password" name="password" placeholder="Password" value="">
                    <i class="fa-solid fa-lock"></i>
                    <?php
                    if (strlen($passErr) !== 0) {
                        echo "<p class='error'>$passErr</p>";
                    }
                    ?>
                    <span id="togglePassword" style="
                    position: absolute;
                    top: 50%;
                    right: 10px;
                    transform: translateY(-50%);
                    cursor: pointer;">
                        <i class="fa-solid fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
                <?php
                if (strlen($invalid) !== 0) {
                    echo "<p class='error'>$invalid</p>";
                }
                ?>

                <button id="btn" type="submit">Login</button>
                <div style="margin-top: 10px; text-align: center; display: block;">
    <a href="#" onclick="showNotification()" style="color: white; text-decoration: none; display: block; margin-bottom: 10px;">
    <i class="fa-solid fa-key"></i> Forgot Password?
</a>

    <a href="../../index.php" style="color: white; text-decoration: none; display: block;">
        <i class="fa-solid fa-house"></i> Back To Home
    </a>
</div>



            </form>
        </div>
    </div>
    <script>
  function showNotification() {
    const bar = document.getElementById("notificationBar");
    bar.style.top = "30px"; // Slide down

    // Auto-hide after 5 seconds
    setTimeout(() => {
      hideNotification();
    }, 10000);
  }

  function hideNotification() {
    const bar = document.getElementById("notificationBar");
    bar.style.top = "-100px"; // Slide up
  }
</script>
    <script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });
    </script>

</body>

</html>