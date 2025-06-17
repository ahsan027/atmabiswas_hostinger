<?php
include '../Database/db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login/loging.php");
    exit();
}
$db = new Db();
$connection = $db->connect();
$connection1 = $db->connect();
$job_id = $_GET['id'];


$sql = "SELECT * FROM jobs WHERE job_id = :job_id";
$stmt = $connection->prepare($sql);

$stmt->bindParam(":job_id", $job_id);

$stmt->execute();

$existing = $stmt->fetchAll(PDO::FETCH_ASSOC);


$updatedValue = [
    ':job_title'      => (isset($_POST['job_title']) && $_POST['job_title']) ? $_POST['job_title'] : $existing[0]['job_title'],

    ':deadline'       => (isset($_POST['deadline']) && $_POST['deadline']) ? $_POST['deadline'] : $existing[0]['deadline'],

    ':job_dept'       => (isset($_POST['job_dept']) && $_POST['job_dept']) ? $_POST['job_dept'] : $existing[0]['job_dept'],

    ':job_location'   => (isset($_POST['job_location']) && $_POST['job_location']) ? $_POST['job_location'] : $existing[0]['job_location'],

    ':salary_range'   => (isset($_POST['salary_range']) && $_POST['salary_range']) ? $_POST['salary_range'] : $existing[0]['salary_range'],

    ':job_experience' => (isset($_POST['job_experience']) && $_POST['job_experience']) ? $_POST['job_experience'] : $existing[0]['job_experience'],

    ':job_skillset'   => (isset($_POST['job_skillset']) && $_POST['job_skillset']) ? $_POST['job_skillset'] : $existing[0]['job_skillset'],

    ':job_description' => (isset($_POST['job_description']) && $_POST['job_description']) ? $_POST['job_description'] : $existing[0]['job_description'],

    ':job_req'        => (isset($_POST['job_req']) && $_POST['job_req']) ? $_POST['job_req'] : $existing[0]['job_req'],

    ':job_benefits'   => (isset($_POST['job_benefits']) && $_POST['job_benefits']) ? $_POST['job_benefits'] : $existing[0]['job_benefits'],

    ':vacancy' => (isset($_POST['vacancy']) && $_POST['vacancy']) ? $_POST['vacancy'] : $existing[0]['vacancy']


];

$newstring = [];
foreach ($updatedValue as $key => $value) {
    $field = str_replace(":", '', $key);
    $newstring[] = "$field" . "=" . ":$field";
}


$sqli = "UPDATE jobs SET " . implode(", ", $newstring) . " WHERE job_id = $job_id";

$stmt1 = $connection1->prepare($sqli);
$stmt1->execute($updatedValue);
if ($stmt1->rowCount() > 0) {
    header("Location: updatejobs.php");
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Update Job - Admin Dashboard</title>
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
                <form method="POST" action="">
                    <div class="formfirst">
                        <header>Update Job Post (Job id: <span style="color: blue;"> <?php echo $job_id; ?> </span> )
                        </header>
                        <div class="details personal">
                            <span class="title">Job Details</span>
                            <div class="fields">
                                <!-- Job title -->

                                <div class="input-field">
                                    <label>Job Position</label>
                                    <select id="jobPosition" name="job_title">
                                        <option value="">Select Position..</option>

                                    </select>
                                </div>


                                <!-- Job code -->


                                <input type="hidden" id="jobcode" name="job_code">



                                <div class="input-field">
                                    <label>Application Deadline</label>
                                    <input name="deadline" type="date" placeholder="Enter Application Deadline">
                                </div>

                                <div class="input-field">
                                    <label>Job Sector</label>
                                    <select name="job_dept">
                                        <option disabled selected>Select Sector</option>
                                        <?php

                                        $sql = "SELECT sector_name FROM sectors ORDER BY sector_name ASC";

                                        $stmtSql =

                                            $connection->prepare($sql);

                                        $stmtSql->execute();

                                        $res = $stmtSql->fetchAll(PDO::FETCH_ASSOC);

                                        print_r($res);

                                        foreach ($res as $r) {
                                            echo '<option value="' . htmlspecialchars($r["sector_name"]) . '">' . htmlspecialchars($r["sector_name"]) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>


                                <div class="input-field">
                                    <label>Job Location</label>
                                    <input name="job_location" type="text" placeholder="Enter Job Location">
                                </div>

                                <div class="input-field">
                                    <label>Salary Range</label>
                                    <input name="salary_range" type="text" placeholder="BDT 000 - BDT 999">
                                </div>

                                <div class="input-field">
                                    <label>Vacancy</label>
                                    <input name="vacancy" type="text" placeholder="1">
                                </div>

                            </div>
                        </div>
                        <div class="details ID">
                            <span class="title">Job Details</span>
                            <div>
                                <div class="fields">
                                    <div class="spinput-field">
                                        <label> Job Experience</label>
                                        <input name="job_experience" type="text"
                                            placeholder="Experience required: eg: 5 years">
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
                                            placeholder="Use fullstop(.) at the end of a Requirement."></textarea>
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
                            <span class="btnText">Update Job</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <script src="js/dashboard.js"></script>
    <script src="js/jobSelection.js"></script>

</body>

</html>