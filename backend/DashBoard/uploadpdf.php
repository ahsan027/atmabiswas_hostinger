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
    <title>Upload Notice - Admin Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="css/uploadfile.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="icon" type="image/png" href="../images/logo/logo.png">
</head>

<body class="bg-gray-100 w-screen h-screen overflow-x-hidden">
    <div class="flex h-screen">
        <div class="flex">
            <?php include 'sidebar.php' ?>
        </div>
        <!-- Main Content -->
        <div class="flex justify-center h-screen">
            <!-- Content Area -->
            <div class="upload-container w-screen h-screen">
                <form action="../../uploadpdf_process.php" method="POST" enctype="multipart/form-data">

                    <div class="upload-section pdf-upload">
                        <div class="mb-3">
                            <i class="bi bi-file-pdf fs-1 text-danger"></i>
                            <h4 class="my-3">Upload PDF</h4>
                            <p class="text-muted">Max file size: 10MB</p>
                            <label for="pdfUpload" class="btn mt-2 btn-danger px-2">
                                Choose PDF
                                <input type="file" id="pdfUpload" name="pdf_file" class="file-input"
                                    accept="application/pdf" required>
                            </label>
                            <div class="preview-container pdf-preview" id="pdfPreview">
                                <i class="bi bi-file-pdf fs-2"></i>
                                <p class="filename mb-0"></p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pdf Title</label>
                        <input class="form-control" id="description" name="pdf_title" placeholder="Add pdf Title..." />
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
        document.getElementById('pdfUpload').addEventListener('change', function(e) {
            const preview = document.getElementById('pdfPreview');
            const file = e.target.files[0];

            if (file) {
                preview.style.display = 'block';
                preview.querySelector('.filename').textContent = file.name;
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