<?php
include '../Database/db.php';
$database = new Db();
$connection = $database->connect();
$connection1 = $database->connect();


$jobId = htmlspecialchars($_GET['id']);
$jobCode = htmlspecialchars($_GET['deptCode']);

$sql = "SELECT * FROM jobs WHERE job_id =:job_id";

$stmt = $connection->prepare($sql);
$stmt->bindParam(":job_id", $jobId);
$stmt->execute();
$jobDes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql1 = "SELECT vacancy FROM jobs WHERE job_id=:job_id;";
$stmt1 = $connection1->prepare($sql1);
$stmt1->bindParam(":job_id", $jobId);
$stmt1->execute();
$deptCode = $stmt1->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details - Senior Software Engineer</title>
    <link rel="stylesheet" href="css/jobdes.css">
</head>

<body>
    <div class="container">
        <div class="job-header">
            <h1 class="job-title"><?= $jobDes[0]['job_title'] ?></h1>
            <div class="company-info">
                <img src="../images/logo/logo.png" alt="Company Logo" class="company-logo">
                <div>
                    <h3>ATMABISWAS.</h3>
                    <p>A non-profitable Organisation</p>
                </div>
            </div>
            <div class="job-meta">
                <div class="meta-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <?php 
                    if($jobDes[0]['job_location'] ==="Negotiable"){
echo '<span>Location: ' . htmlspecialchars($jobDes[0]["job_location"]) . '</span>';


                    }else{
                        echo '<span>Location: ' . htmlspecialchars($jobDes[0]["job_location"]) . ', Bangladesh</span>';

                    }
                    ?>
                </div>
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span>Full-time</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar-times"></i>
                    <span>DeptCode: <?= $jobCode ?></span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar-times"></i>
                    <span class="deadline">Application Deadline: <?= $jobDes[0]['deadline'] ?></span>
                </div>

            </div>
        </div>

        <div class="job-content">
            <div class="main-content">
                <div class="section">
                    <h2>Job Description</h2>
                    <p>We are looking for a skilled <strong
                            style="color:#3498db;"><?= $jobDes[0]['job_title'] ?></strong>
                        who has expertise in
                        <strong><?= $jobDes[0]['job_skillset'] ?></strong> To join our growing team...
                    </p>
                    <br>
                    <ul class="job-description-list">
                        <?php
                        $description = $jobDes[0]['job_description'];

                        // Example enhancement (adjust keywords as needed)
                        $description = htmlspecialchars($description);
                        $description = str_replace("Responsibilities", "<span class='highlight-blue'><strong>Responsibilities</strong></span>", $description);
                        $description = str_replace("Requirements", "<span class='highlight-blue'><strong>Requirements</strong></span>", $description);
                        $description = str_replace("•", "<li>", $description); // convert bullets to list items
                        $description = nl2br($description); // convert newlines to <br>

                        // Close open <li> tags after each line break
                        $description = preg_replace('/<li>(.*?)<br\s*\/?>/i', '<li>$1</li>', $description);

                        echo $description;
                        ?>
                    </ul>

                </div>

                <div class="section">
                    <h2>Requirements</h2>
                    <ul class="job-req-list">
                        <?php
                        $requirements = htmlspecialchars($jobDes[0]['job_req']);

                        // Highlight keywords (add more if needed)
                        $requirements = str_replace("Qualifications", "<span class='highlight-blue'><strong>Qualifications</strong></span>", $requirements);
                        $requirements = str_replace("Experience", "<span class='highlight-blue'><strong>Experience</strong></span>", $requirements);
                        $requirements = str_replace("•", "<li>", $requirements); // Convert bullet points to <li>
                        $requirements = nl2br($requirements); // Convert newlines to <br>

                        // Properly close each <li> tag
                        $requirements = preg_replace('/<li>(.*?)<br\s*\/?>/i', '<li>$1</li>', $requirements);

                        echo $requirements;
                        ?>
                    </ul>

                </div>

                <div class="section">
                    <h2>Benefits</h2>
                    <ul class="job-benefits-list">
                        <?php
                        $benefits = htmlspecialchars($jobDes[0]['job_benefits']);

                        // Highlight keywords
                        $benefits = str_replace("Benefits", "<span class='highlight-blue'><strong>Benefits</strong></span>", $benefits);
                        $benefits = str_replace("Perks", "<span class='highlight-blue'><strong>Perks</strong></span>", $benefits);
                        $benefits = str_replace("•", "<li>", $benefits); // Convert bullets to list items
                        $benefits = nl2br($benefits); // Convert newlines to <br>

                        // Close each <li> after <br>
                        $benefits = preg_replace('/<li>(.*?)<br\s*\/?>/i', '<li>$1</li>', $benefits);

                        echo $benefits;
                        ?>
                    </ul>

                </div>
            </div>

            <div class="sidebar">
                <div class="section">
                    <h2>Job Overview</h2>
                    <div class="meta-item" style="margin-bottom: 15px;">
                        <i class="fas fa-calendar"></i>
                        <span>Posted: <?= $jobDes[0]['PostDate']; ?></span>
                    </div>
                    <div class="meta-item" style="margin-bottom: 15px;">
                        <i class="fas fa-users"></i>
                        <span>Vacancy: <?php
                                        echo $deptCode[0]['vacancy'];
                                        ?> </span>
                    </div>
                    <div class="meta-item" style="margin-bottom: 15px;">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Salary: <?= $jobDes[0]['salary_range']; ?> (Negotiable)</span>
                    </div>
                </div>

                <button class="apply-button" onclick="openApplyModal()">Apply Now</button>
            </div>
        </div>
    </div>

    <!-- Application Modal -->
    <div class="modal" id="applyModal">
        <div class="modal-content">
            <h2>Apply for Senior Software Engineer</h2>
            <form method="POST" id="applicationForm" action="../../sendingMail.php" enctype="multipart/form-data">
                <div class="form-group">

                    <!-- Hidden input fields to send values -->
                    <input type="hidden" name="job_id" value="<?php echo $jobId ?>">

                    <input type="hidden" name="job_code" value="<?php echo $jobCode ?>">

                    <input type="hidden" value="<?= $jobDes[0]['job_title'] ?>" name="job-title">


                    <label>Full Name</label>
                    <input name="fullname" type="text" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input name="email" type="email" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input name="phone" type="tel" required>
                </div>
                <div class="form-group">
                    <label>Cover Letter</label>
                    <textarea name="mailbody" rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <label>Upload CV</label>
                    <input name="cvfile" type="file" accept=".pdf" required>
                </div>
                <button type="submit" class="apply-button">Submit Application</button>
            </form>
        </div>
    </div>

    <script>
    function openApplyModal() {
        document.getElementById('applyModal').style.display = 'block';
        document.getElementById('applyModal').style.overflowY = 'auto';

    }

    function closeApplyModal() {
        document.getElementById('applyModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('applyModal');
        if (event.target === modal) {
            closeApplyModal();
        }
    }
    </script>
</body>

</html>