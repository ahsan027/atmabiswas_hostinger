<?php
include '../Database/db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login/loging.php");
    exit();
}
$db = new Db();
$connection = $db->connect();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Job Position & Sector - ATMABISWAS</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/uploadfile.css">
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="icon" type="image/png" href="../images/logo/logo.png">
</head>

<body class="bg-gray-50">
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar-container">
            <?php include 'sidebar.php' ?>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header Section -->
            <div class="page-header">
                <div class="header-content">
                    <div class="header-left">
                        <h1 class="page-title">
                            <i class="fas fa-briefcase"></i>
                            Add Job Position
                        </h1>
                        <p class="page-subtitle">Create new job positions and sectors</p>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <section class="form-section">
                <div class="form-container">
                    <!-- Notification -->
                    <div id="errorNotification" class="notification">Please fill in both fields!</div>

                    <form action="../addJob_processing.php" method="POST" class="job-form">
                        <div class="form-header">
                            <h2 class="form-title">
                                <i class="fas fa-plus-circle"></i>
                                Create New Job Position
                            </h2>
                            <p class="form-description">Add a new job position and sector to the system</p>
                        </div>

                        <div class="form-body">
                            <div class="form-group">
                                <label for="jobtitle" class="form-label">
                                    <i class="fas fa-briefcase"></i>
                                    Job Position Title
                                </label>
                                <input
                                    type="text"
                                    id="jobtitle"
                                    name="jobtitle"
                                    class="form-input"
                                    placeholder="Enter job position title..."
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="jobsector" class="form-label">
                                    <i class="fas fa-building"></i>
                                    Job Sector
                                </label>
                                <input
                                    type="text"
                                    id="jobsector"
                                    name="jobsector"
                                    class="form-input"
                                    placeholder="Enter job sector..."
                                    required />
                            </div>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                Add Job Position
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
    <script src="js/dashboard.js"></script>

    <script>
        // Form validation
        document.querySelector('.job-form').addEventListener('submit', function(e) {
            const title = document.querySelector('input[name="jobtitle"]').value.trim();
            const sector = document.querySelector('input[name="jobsector"]').value.trim();

            if (title === "" || sector === "") {
                e.preventDefault();
                showNotification('Please fill in both fields!');
            }
        });

        // Notification system
        function showNotification(message) {
            const notif = document.getElementById('errorNotification');
            notif.textContent = message;
            notif.classList.add('show');

            setTimeout(() => {
                notif.classList.remove('show');
            }, 3000);
        }

        // Add smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.job-form');
            form.style.opacity = '0';
            form.style.transform = 'translateY(20px)';

            setTimeout(() => {
                form.style.transition = 'all 0.5s ease';
                form.style.opacity = '1';
                form.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>

</body>

</html>