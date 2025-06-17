<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login/loging.php");
    exit();
}

include '../Database/db.php';

$db = new Db();

$conn = $db->connect();
function concatStrings($blog): string
{
    $display = "";
    $maximumLength = 80;

    $display = (strlen($blog) > $maximumLength) ? substr($blog, 0, $maximumLength) . "...." : $blog;

    return $display;
}
try {

    $sql = "SELECT * FROM blogs LIMIT 5";

    $stmt = $conn->prepare($sql);

    $stmt->execute();

    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e;
}

try {
    $imgSql = "SELECT * FROM img_upload LIMIT 5";

    $imgStmt = $conn->prepare($imgSql);

    $imgStmt->execute();

    $images = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e;
}

try {
    $pdfSql = "SELECT * FROM pdsfiles LIMIT 5";

    $pdfStmt = $conn->prepare($pdfSql);

    $pdfStmt->execute();

    $pdfS = $pdfStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e;
}

try {
    $jobSql = "SELECT * FROM jobcodes LIMIT 5";

    $jobStmt = $conn->prepare($jobSql);

    $jobStmt->execute();

    $jobs = $jobStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e;
}


try {
    $cvSql = "SELECT * FROM cv_applications LIMIT 5";

    $cvStmt = $conn->prepare($cvSql);

    $cvStmt->execute();

    $cvs = $cvStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e;
}

try {
    $secSql = "SELECT * FROM sectors LIMIT 5";

    $secStmt = $conn->prepare($secSql);

    $secStmt->execute();

    $sectors = $secStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard-ATMABISWAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="icon" type="image/png" href="../images/logo/logo.png">
</head>

<style>
.admin-welcome {
    margin-top: 1rem;
    padding: 16px 20px;
    background: #f0f4f8;
    border-left: 5px solid #3b82f6;
    border-radius: 12px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 16px;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    animation: fadeIn 0.8s ease-in-out;
}

/* Username styling */
.admin-name {
    font-weight: bold;
    padding: 6px 12px;
    background-color: #f8f9fa;
    /* off-white */
    color: #1e3a8a;
    border-radius: 20px;
    transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    cursor: default;
}

.admin-name:hover {
    background-color: #dbeafe;
    /* light blue hover */
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.admin-name .role {
    font-weight: normal;
    font-size: 14px;
    opacity: 0.7;
    margin-left: 6px;
}

/* Link styling */
.create-admin-link {
    margin-left: auto;
    text-decoration: none;
    color: #2563eb;
    font-weight: 500;
    transition: color 0.3s, text-decoration 0.3s;
}

.create-admin-link:hover {
    text-decoration: underline;
    color: #1d4ed8;
}

/* Entrance animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(8px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include 'sidebar.php' ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden overflow-x-hidden">
            <!-- Top Navbar -->

            <?php include 'navbar.inc.php'; ?>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto p-4 bg-gray-100">
                <div class="mb-6">
                    <h2 class="text-3xl font-semibold text-gray-800">
                        Dashboard Overview
                    </h2>
                    <p class="admin-welcome">
                        Welcome back, <span class="admin-name"><?php echo $_SESSION['username']; ?>
                            <span class="role">(ADMIN)</span></span>
                        <a href="adminSignup.php" class="create-admin-link">Create a new Admin?</a>
                    </p>

                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Revenue Card -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-gray-500">Avaiable Job Positions</div>
                            <div class="bg-green-100 p-1 rounded">
                                <i class="fas fa-briefcase text-green-600"></i>

                            </div>
                        </div>
                        <div class="text-3xl font-bold"><?php
                                                        $countJobs = "SELECT COUNT(*) AS total_jobs FROM jobcodes";


                                                        $jobStmt = $conn->prepare($countJobs);

                                                        $jobStmt->execute();

                                                        $countBj = $jobStmt->fetchAll(PDO::FETCH_ASSOC);

                                                        echo $countBj[0]['total_jobs'];


                                                        ?></div>
                        <div class="mt-3 mt-3 flex items-center text-sm">
                            <span class="text-green-600 flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i> Avaiable
                            </span>
                            <span class="text-gray-500 ml-2">Jobs</span>
                        </div>
                    </div>

                    <!-- Orders Card -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-gray-500">Job Request</div>
                            <div class="bg-blue-100 p-1 rounded">
                                <i class="fas fa-file-alt text-blue-600"></i>

                            </div>
                        </div>
                        <div class="text-3xl font-bold"><?php
                                                        $countpen = "SELECT COUNT(*) AS total_jobs FROM cv_applications";


                                                        $penStmt = $conn->prepare($countpen);

                                                        $penStmt->execute();

                                                        $penBj = $penStmt->fetchAll(PDO::FETCH_ASSOC);

                                                        echo $penBj[0]['total_jobs'];


                                                        ?></div>
                        <div class="mt-3 flex items-center text-sm">
                            <span class="text-green-600 flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i> Pending Job
                            </span>
                            <span class="text-gray-500 ml-2">Request</span>
                        </div>
                    </div>

                    <!-- Customers Card -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-gray-500">Total Sectors</div>
                            <div class="bg-purple-100 p-1 rounded">
                                <i class="fas fa-th-large text-purple-600"></i>

                            </div>
                        </div>
                        <div class="text-3xl font-bold"><?php
                                                        $sect = "SELECT COUNT(*) AS total_jobs FROM sectors";


                                                        $newSecStmt = $conn->prepare($sect);

                                                        $newSecStmt->execute();

                                                        $secBj = $newSecStmt->fetchAll(PDO::FETCH_ASSOC);

                                                        echo $secBj[0]['total_jobs'];


                                                        ?></div>
                        <div class="mt-3 flex items-center text-sm">
                            <span class="text-green-600 flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i> Total Job
                            </span>
                            <span class="text-gray-500 ml-2">Sectors</span>
                        </div>
                    </div>

                    <!-- Conversion Card -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-gray-500">Uploaded Contents</div>
                            <div class="bg-yellow-100 p-1 rounded">
                                <i class="fas fa-book-open text-yellow-600"></i>

                            </div>
                        </div>
                        <ul class="text-green-600 font-bold cursor-pointer hover:text-blue-600">
                            <li><?php
                                $sect = "SELECT COUNT(*) AS total_jobs FROM blogs";


                                $newSecStmt = $conn->prepare($sect);

                                $newSecStmt->execute();

                                $secBj = $newSecStmt->fetchAll(PDO::FETCH_ASSOC);

                                echo $secBj[0]['total_jobs']; ?> Published News</li>
                            <li><?php
                                $sect = "SELECT COUNT(*) AS total_jobs FROM pdsfiles";


                                $newSecStmt = $conn->prepare($sect);

                                $newSecStmt->execute();

                                $secBj = $newSecStmt->fetchAll(PDO::FETCH_ASSOC);

                                echo $secBj[0]['total_jobs']; ?> Notices</li>
                            <li><?php
                                $sect = "SELECT COUNT(*) AS total_jobs FROM img_upload";


                                $newSecStmt = $conn->prepare($sect);

                                $newSecStmt->execute();

                                $secBj = $newSecStmt->fetchAll(PDO::FETCH_ASSOC);

                                echo $secBj[0]['total_jobs']; ?> Images</li>
                        </ul>
                        <div class="mt-3 flex items-center text-sm">

                        </div>
                    </div>
                </div>

                <!-- Jobs table -->

                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-semibold text-lg">Job Positions and Distinct Job code Listing</h3>
                        <button onclick="handleButton()"
                            class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 text-sm">
                            View All</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Job ID
                                    </th>
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Job Position
                                    </th>
                                    <th
                                        class="py-3 px-4 min-w-[150px] max-w-[250px] text-left text-sm font-medium text-gray-500 uppercase tracking-wider break-words">
                                        Job Code
                                    </th>


                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php


                                foreach ($jobs as $job) {

                                    echo '<tr class="border-b">
                                    <td class="py-3 px-4 text-gray-700">' . $job['jobid'] . '</td>
                                    <td class="py-3 px-4 text-gray-700">' . concatStrings($job['JobTitle']) . '</td>
                                    <td class="py-3 px-4">
                                        <span class="text-center inline-block w-[70px] px-1 py-1 text-[1rem] text-white bg-blue-500 rounded text-wrap font-bold tracking-wider">' . $job['JobCode'] . '</span>
                                    </td>
                    
                        
                                    <td class="py-3 px-4">
                                      <a onclick="return confirm(\'Are you sure you want to delete this blog?\');" href="../deleteJobPositions.php?job_id=' . $job['jobid'] . '" class="bg-red-500 text-white font-bold px-4 py-2 rounded cursor-pointer">
                                      Delete
                                    </td>
                                </tr>';
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Job Sectors -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-semibold text-lg">Job Sector Listing</h3>
                        <button onclick="handleButton()"
                            class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 text-sm">
                            View All</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Sector ID
                                    </th>
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Sector Name
                                    </th>


                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php


                                foreach ($sectors as $sec) {

                                    echo '<tr class="border-b">
                                    <td class="py-3 px-4 text-gray-700">' . $sec['sector_id'] . '</td>
                                    <td class="py-3 px-4 text-gray-700">' . $sec['sector_name'] . '</td>
                                    
                        
                                    <td class="py-3 px-4">
                                      <a onclick="return confirm(\'Are you sure you want to delete this Sector?\');" href="../deleteSector.php?sec_id=' . $sec['sector_id'] . '" class="bg-red-500 text-white font-bold px-4 py-2 rounded cursor-pointer">
                                      Delete
                                    </td>
                                </tr>';
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>



                <!-- Pending Application -->

                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-semibold text-lg">Pending Job Request</h3>
                        <button onclick="handleButton()"
                            class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 text-sm">
                            View All</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Application ID
                                    </th>
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Job ID
                                    </th>

                                    <th
                                        class="py-3 px-4 min-w-[150px] max-w-[250px] text-left text-sm font-medium text-gray-500 uppercase tracking-wider break-words">
                                        Job Title
                                    </th>

                                    <th
                                        class="py-3 px-4 min-w-[150px] max-w-[250px] text-left text-sm font-medium text-gray-500 uppercase tracking-wider break-words">
                                        Full Name
                                    </th>
                                    <th
                                        class="py-3 px-4 min-w-[150px] max-w-[250px] text-left text-sm font-medium text-gray-500 uppercase tracking-wider break-words">
                                        Email
                                    </th>

                                    <th
                                        class="py-3 px-4 min-w-[150px] max-w-[250px] text-left text-sm font-medium text-gray-500 uppercase tracking-wider break-words">
                                        Phone
                                    </th>

                                    <th
                                        class="py-3 px-4 min-w-[150px] max-w-[250px] text-left text-sm font-medium text-gray-500 uppercase tracking-wider break-words">
                                        Applied At
                                    </th>

                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php


                                foreach ($cvs as $cv) {

                                    echo '<tr class="border-b">
                                    <td class="text-center py-3 px-4 text-gray-700">' . $cv['applicationId'] . '</td>
                                    <td class="text-center py-3 px-4 text-gray-700">' . $cv['jobId'] . '</td>
                                    <td class="py-3 px-4">
                                        <span class="text-center text-gray-700">' . $cv['job_title'] . '</span>
                                    </td>

                                        <td class="text-gray-700 py-3 px-4">' . $cv['fullname'] . '
                                    </td>

                                           <td class="text-gray-700 py-3 px-4">' . $cv['email'] . '
                                    </td>

                                      <td class="text-gray-700 py-3 px-4">' . '+88' . $cv['phone_no'] . '
                                    </td>

                                              <td class="text-gray-700 py-3 px-4">' . $cv['appliedAt'] . '
                                    </td>
                    
                    
                        
                                    <td class="py-3 px-4">
                                      <a onclick="return confirm(\'Are you sure you want to delete this Job Request?\');" href="../../deletePendingJobs.php?applicationId=' . $cv['applicationId'] . '" class="bg-red-500 text-white font-bold px-4 py-2 rounded cursor-pointer">
                                      Delete
                                    </td>
                                </tr>';
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>



                <!-- blogs Table -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-semibold text-lg">Published News Lists</h3>
                        <button onclick="handleButton()"
                            class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 text-sm">
                            View All
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Blog ID
                                    </th>
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Blog Title
                                    </th>
                                    <th
                                        class="py-3 px-4 min-w-[150px] max-w-[250px] text-left text-sm font-medium text-gray-500 uppercase tracking-wider break-words">
                                        Blog Author
                                    </th>

                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Published Date
                                    </th>
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php


                                foreach ($blogs as $blog) {

                                    echo '<tr class="border-b">
                                    <td class="py-3 px-4 text-gray-700">' . $blog['blog_id'] . '</td>
                                    <td class="py-3 px-4 text-gray-700">' . concatStrings($blog['blog_title']) . '</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-2 text-xs text-white bg-blue-500 rounded">' . $blog['blog_author'] . '</span>
                                    </td>
                           
                                    <td class="py-3 px-4 text-gray-700">' . $blog['upload_date'] . '</td>
                                    <td class="py-3 px-4">
                                      <a onclick="return confirm(\'Are you sure you want to delete this blog?\');" href="../../deleteblog.php?blog_id=' . $blog['blog_id'] . '" class="bg-red-500 text-white font-bold px-4 py-2 rounded cursor-pointer">
                                      Delete
                                    </td>
                                </tr>';
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>



                <!-- images Table -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-semibold text-lg">Recently Uploaded Images</h3>
                        <button onclick="handleButton()"
                            class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 text-sm">
                            View All
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Image ID
                                    </th>
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Image Title
                                    </th>
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Description
                                    </th>
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Image Path
                                    </th>
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Upload Date
                                    </th>
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($images as $image) {
                                    echo '<tr class="border-b">
                                    <td class="py-3 px-4 text-gray-700">' . $image['img_id'] . '</td>
                                    <td class="py-3 px-4 text-gray-700">' . $image['img_title'] . '</td>
                                    <td class="py-3 px-4">
                                        <span class="text-gray-700 ">' . concatStrings($image['img_description']) . '</span>
                                    </td>
                                    <td class="py-3 px-4 text-gray-700">' . concatStrings($image['img_path']) . '</td>
                                    <td class="py-3 px-4 text-gray-700">' . $image['uploaded_on'] . '</td>
                                    <td class="py-3 px-4">
                                  <a onclick="return confirm(\'Are you sure you want to delete this image?\')" href="../../deleteimage.php?img_id=' . $image['img_id'] . '" class="bg-red-500 text-white font-bold px-4 py-2 rounded cursor-pointer">
                                        Delete
                                    </a>
                                    </td>
                                </tr>';
                                }

                                ?>



                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- pdf Table -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-semibold text-lg">Uploaded pdf files...</h3>
                        <button onclick="handleButton()"
                            class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 text-sm">
                            View All
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        PDF ID
                                    </th>
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        PDF Title
                                    </th>
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        PDF path
                                    </th>
                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Upload Date
                                    </th>

                                    <th
                                        class="py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($pdfS as $pdf) {
                                    echo '<tr class="border-b">
                                    <td class="py-3 px-4 text-gray-700">' . $pdf['pdf_id'] . '</td>
                                    <td class="py-3 px-4 text-gray-700">' . $pdf['pdf_title'] . '</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 text-gray-700 ">' . $pdf['pdf_path'] . '</span>
                                    </td>
                                    <td class="py-3 px-4 text-gray-700">' . $pdf['upload_date'] . '</td>
                                    <td class="py-3 px-4">
                                  <a onclick="return confirm(\'Are you sure you want to delete this pdf?\')" href="../../deletepdf.php?pdf_id=' . $pdf['pdf_id'] . '" class="bg-red-500 text-white font-bold px-4 py-2 rounded cursor-pointer">
                                        Delete
                                    </a>
                                    </td>
                                </tr>';
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>
    <script src="js/dashboard.js"></script>
</body>

</html>