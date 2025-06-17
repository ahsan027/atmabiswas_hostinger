<?php
include '../Database/db.php';
session_start();
if (!isset($_SESSION['username'])) {

    header("Location: ../login/loging.php");
    exit();
}

$db = new Db();
$connection = $db->connect();

$sql = "SELECT * FROM sectors";

$stmt = $connection->prepare($sql);

$stmt->execute();

$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Job - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="css/createjob.css">
    <link rel="icon" type="image/png" href="../images/logo/logo.png">
</head>

<body class="bg-gray-100 overflow-x-hidden">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include 'sidebar.php' ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navbar -->



            <!-- Content Area -->
            <div class="container">
                <form method="POST" action="Actions/jobreq.php">
                    <div class="formfirst">
                        <header>Create Job Post</header>
                        <div class="details personal">
                            <span class="title">Job Details</span>
                            <div class="fields">
                                <!-- Job title -->

                                            <div class="input-field">
                                    <label>Job Sector</label>
                                    <select name="job_dept" required>
                                        <option disabled selected>Select Sector</option>
                                        <?php
                                        foreach ($res as $r) {
                                            echo '<option value="' . htmlspecialchars($r["sector_name"]) . '">' . htmlspecialchars($r["sector_name"]) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="input-field">
                                    <label>Job Position</label>
                                    <select id="jobPosition" name="job_title" required>
                                        <option value="">Select Position..</option>

                                    </select>
                                </div>


                                <!-- Job code -->


                                <input type="hidden" id="jobcode" name="job_code" required>



                                <div class="input-field">
                                    <label>Application Deadline</label>
                                    <input name="deadline" type="date" placeholder="Enter Application Deadline"
                                        required>
                                </div>


                                <div class="input-field">
                                    <label>Job Location</label>
                                    <input name="job_location" type="text" placeholder="Enter Job Location" required>
                                </div>

                                <div class="input-field">
                                    <label>Salary Range</label>
                                    <input name="salary_range" type="text" placeholder="BDT 000 - BDT 999" required>
                                </div>

                                <div class="input-field">
                                    <label>Vacancy</label>
                                    <input name="vacancy" type="text" placeholder="1" required>
                                </div>

                            </div>
                        </div>
                        <div class="details ID">
                            <span class="title">Job Details</span>
                            <div>
                                <div class="fields">
                                    <div class="spinput-field">
                                        <label>Required Job Experience</label>
                                        <input name="job_experience" type="text"
                                            placeholder="Enter required Experience">
                                    </div>
                                    <div class="spinput-field">
                                        <label>Job Skillset</label>
                                        <input name="job_skillset" type="text"
                                            placeholder="eg: PHP, JavaScript, MySQL, REST APIs, Frontend frameworks (React or Angular).">
                                    </div>
                                </div>
                                <div class="fields">
                                    <div class="spinput-field">
                                        <label>Job Description</label>
                                        <textarea name="job_description"
                                            placeholder="Use fullstop(.) at the end of a description."></textarea>
                                    </div>
                                    <div class="spinput-field">
                                        <label>Job Requirements</label>
                                        <textarea name="job_req"
                                            placeholder="Use fullstop(.) at the end of a Requirement."
                                            required></textarea>
                                    </div>
                                    <div class="spinput-field">
                                        <label>Job Benefits</label>
                                        <textarea name="job_benefits"
                                            placeholder="Use fullstop(.) at the end of a Beneifit."></textarea>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <button type="submit" class="nextBtn">
                            <span class="btnText">Create Job</span>
                        </button>
                    </div>



                </form>
            </div>

        </div>
    </div>
    <script src="js/dashboard.js"></script>
    <script src="js/createjob.js"></script>
    <script src="js/jobSelection.js"></script>

</body>

</html>