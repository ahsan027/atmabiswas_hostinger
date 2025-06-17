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
    <title>Upload Images - Admin Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="css/uploadfile.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="icon" type="image/png" href="../images/logo/logo.png">

</head>

<body class="bg-gray-100 overflow-x-hidden">
    <div class="flex h-screen">
        <div class="flex overflow-y-hidden">
            <?php include 'sidebar.php' ?>
        </div>
        <!-- Main Content -->
        <div class="flex justify-center h-screen">
            <!-- Content Area -->
            <div class="upload-container w-screen h-screen">

                <form action="../../uploadimg_process.php" method="POST" enctype="multipart/form-data">

                    <div class="upload-section image-upload">
                        <div class="mb-3">
                            <i class="bi bi-image fs-1 text-primary"></i>
                            <h4 class="my-3">Upload Image</h4>
                            <p class="text-muted">Supported formats: JPG, JPEG, PNG
                                (Max 2MB)</p>
                            <label for="imageUpload" class="btn mt-2 btn-primary px-2">
                                Choose Image
                                <input type="file" id="imageUpload" name="image_file" class="file-input"
                                    accept=".jpg, .jpeg, .png" required>
                            </label>
                            <div class="preview-container" id="imagePreview">
                                <img src="#" class="img-thumbnail mt-2" alt="Image preview" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="mb-4">
                            <label for="imagetype" class="block text-sm font-medium text-gray-700 mb-2">
                                Image Type
                            </label>
                            <select name="imagetype" id="imagetype"
                                class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700">

                                <option selected value="img_slider">Image Slider</option>

                                <option value="latest_news">Latest News</option>

                            </select>
                        </div>



                        <label for="description" class="form-label">Image Title</label>
                        <input
                            class="form-control block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700 mb-3"
                            id="description" name="img_title" placeholder="Add Image Title..." />

                        <label for="description" class="form-label ">Description</label>
                        <textarea
                            class="form-control block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700"
                            id="description" name="img_description" rows="3"
                            placeholder="Add file description..."></textarea>
                    </div>


                    <button type="submit" class="btn btn-success w-100 py-2">
                        <i class="bi bi-cloud-upload me-2"></i>Upload Files
                    </button>
                </form>
            </div>

        </div>
    </div>
    <script src="js/dashboard.js"></script>



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