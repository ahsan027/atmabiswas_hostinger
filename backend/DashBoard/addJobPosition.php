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
    <title>Add Job Position & Sector</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="css/uploadfile.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="icon" type="image/png" href="../images/logo/logo.png">

</head>
<style>
    /* Notification style */
    .notification {
        position: fixed;
        top: -100px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #f87171;
        /* red-400 */
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        font-weight: 500;
        z-index: 9999;
        transition: top 0.5s ease;
    }

    .notification.show {
        top: 20px;
    }
</style>

<body class="bg-gray-100 overflow-x-hidden">
    <div class="flex h-screen">
        <div class="flex overflow-y-hidden">
            <?php include 'sidebar.php' ?>
        </div>
        <!-- Main Content -->
        <div class="flex items-center justify-center h-screen">
            <!-- Content Area -->
            <div id="errorNotification" class="notification">Please fill in both fields!</div>

            <div class="upload-container w-screen h-screen">

                <form action="../addJob_processing.php" method="POST">
                    <h1 class="text-center text-xl font-bold">Add an Unique Job Position</h1>


                    <div class="mb-3">

                        <label for="description" class="form-label">Job Position Title</label>
                        <input
                            class="form-control block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700 mb-3"
                            id="description" name="jobtitle" placeholder="Add a Job position title..." />

                        <label for="description" class="form-label ">Job Sector</label>
                        <input
                            class="form-control block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700"
                            id="description" name="jobsector" rows="3" placeholder="Add an Job Sector..."></input>
                    </div>


                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-cloud-upload me-2"></i>Add Job Position
                    </button>
                </form>
            </div>

        </div>
    </div>
    <script src="js/dashboard.js"></script>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {

            const title = document.querySelector('input[name="jobtitle"]').value.trim();

            const sector = document.querySelector('input[name="jobsector"]').value.trim();

            if (title === "" && sector === "") {
                e.preventDefault();

                const notif = document.getElementById('errorNotification');

                notif.classList.add('show');

                setTimeout(() => {
                    notif.classList.remove('show');
                }, 3000);
            }
        });
    </script>




    <script>
        document.getElementById('imageUpload').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];

            if (file) {
                preview.style.display = 'block';
                const reader = new FileReader();

                reader.onload = function(event) {
                    preview.querySelector('img').src = event.target.result;
                }

                reader.readAsDataURL(file);
            }
        });

        // Drag and drop highlight
        document.querySelectorAll('.upload-section').forEach(section => {
            section.addEventListener('dragover', (e) => {
                e.preventDefault();
                section.classList.add('dragover');
            });

            section.addEventListener('dragleave', () => {
                section.classList.remove('dragover');
            });

            section.addEventListener('drop', (e) => {
                e.preventDefault();
                section.classList.remove('dragover');
            });
        });
    </script>

</body>

</html>