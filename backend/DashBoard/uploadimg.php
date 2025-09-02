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
    <title>Upload Images - ATMABISWAS</title>

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
                            <i class="fas fa-image"></i>
                            Upload Images
                        </h1>
                        <p class="page-subtitle">Upload and manage images for the website</p>
                    </div>
                </div>
            </div>

            <!-- Upload Section -->
            <section class="upload-section">
                <div class="upload-container">

                    <form action="../../uploadimg_process.php" method="POST" enctype="multipart/form-data" class="upload-form">
                        <div class="form-header">
                            <h2 class="form-title">
                                <i class="fas fa-cloud-upload-alt"></i>
                                Upload Image
                            </h2>
                            <p class="form-description">Upload images for the website with proper categorization</p>
                        </div>

                        <div class="upload-area">
                            <div class="upload-zone">
                                <div class="upload-icon">
                                    <i class="fas fa-image"></i>
                                </div>
                                <h3 class="upload-title">Choose Image File</h3>
                                <p class="upload-info">Supported formats: JPG, JPEG, PNG (Max 2MB)</p>
                                <label for="imageUpload" class="upload-btn">
                                    <i class="fas fa-folder-open"></i>
                                    Browse Files
                                    <input type="file" id="imageUpload" name="image_file" class="file-input"
                                        accept=".jpg, .jpeg, .png" required>
                                </label>
                                <div class="preview-container" id="imagePreview">
                                    <img src="#" alt="Image preview">
                                </div>
                            </div>
                        </div>

                        <div class="form-body">
                            <div class="form-group">
                                <label for="imagetype" class="form-label">
                                    <i class="fas fa-tags"></i>
                                    Image Type
                                </label>
                                <select name="imagetype" id="imagetype" class="form-select" required>
                                    <option value="img_slider">Image Slider</option>
                                    <option value="latest_news">Latest News</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="img_title" class="form-label">
                                    <i class="fas fa-heading"></i>
                                    Image Title
                                </label>
                                <input
                                    type="text"
                                    id="img_title"
                                    name="img_title"
                                    class="form-input"
                                    placeholder="Enter image title..."
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="img_description" class="form-label">
                                    <i class="fas fa-align-left"></i>
                                    Description
                                </label>
                                <textarea
                                    id="img_description"
                                    name="img_description"
                                    class="form-textarea"
                                    rows="3"
                                    placeholder="Enter image description..."></textarea>
                            </div>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i>
                                Upload Image
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
    <script src="js/dashboard.js"></script>



    <script>
        // Image preview functionality
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

        // Drag and drop functionality
        const uploadZone = document.querySelector('.upload-zone');

        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });

        uploadZone.addEventListener('dragleave', () => {
            uploadZone.classList.remove('dragover');
        });

        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('dragover');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('imageUpload').files = files;
                document.getElementById('imageUpload').dispatchEvent(new Event('change'));
            }
        });

        // Form animations
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.upload-form');
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