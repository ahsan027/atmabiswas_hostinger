<?php
include 'Backend/Database/db.php';
$db = new Db();
$pdo = $db->connect();
$conn = $db->connect();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATMABISWAS - Contact</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
    <link rel="stylesheet" href="locations.css">


    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background-color: #e6f2ff;
        overflow-x: hidden;
        height: auto;
    }
    </style>



</head>

<body>

    <?php include 'Navbar.php' ?>


    <div class="mainbody">
        <button class="toggle-btn" id="toggle-btn">ATMABISWAS HQ & Liaison Office</button>

        <!-- Contact Card -->
        <div class="contact-card" id="contactCard">
            <div class="newnewdiv">
                <div class="contact-info">
                    <div class="cinfos">
                        <h2>ATMABISWAS Head Office </h2>
                        <p><strong>Branch:</strong> ATMABISWAS, Main Office</p>
                        <p><strong>Address:</strong> Asma Palace,Court Para,Chuadanga-7200 </p>
                        <p><strong>Phone:</strong> +8801713302930</p>
                        <p><strong>Email:</strong> atmabiswas_ngo@yahoo.com</p>
                    </div>
                    <div style="margin-left:3px;" class="btn">
                        <a href="loc.html"><button id="">Other Branchs</button></a>
                    </div>

                </div>

                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3654.990205551057!2d88.84420999999999!3d23.640521800000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fecc6a98f15555%3A0x7237da8c2d53a42d!2sAtmabiswas!5e0!3m2!1sen!2sbd!4v1737729888492!5m2!1sen!2sbd"
                        loading="lazy" allowfullscreen loading="lazy"></iframe>

                </div>

            </div>

            <div class="oldnewdiv">
                <div class="contact-info">
                    <div class="cinfos">
                        <h2>ATMABISWAS Liaison Office </h2>
                        <p><strong>Branch:</strong> ATMABISWAS, Liaison Office</p>
                        <p><strong>Address:</strong> 59, Mia Tower, West Agargaon, BNP bazar, Dhaka - 1207 </p>
                        <p><strong>Phone:</strong> +8801713302930</p>
                        <p><strong>Email:</strong> atmabiswas_ngo@yahoo.com</p>
                    </div>


                </div>

                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.0861266058914!2d90.37095479999999!3d23.7799472!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c0b3f03cfd27%3A0xbec44bc7d01da1f!2sEl%20Dorado%20Mia%20Tower%2C%20House%23%2059%2C%20West%20Agargaon%2C%20Dhaka%201207!5e0!3m2!1sen!2sbd!4v1748789887571!5m2!1sen!2sbd"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

            </div>

        </div>




        <button class="toggle-btn" id="newcard">ATMABISWAS REGIONAL OFFICES</button>

        <div id="reg" class="regbranch">
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Branch Regional Name</th>
                            <th>Address</th>
                            <th>Designation</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
                    <tbody id="newtable">
                    </tbody>
                </table>
            </div>


        </div>



        <button class="toggle-btn" id="filterbutton">ATMABISWAS BRANCHES</button>
        <div class="filterbars" id="filterbars">

            <select name="division" id="divisionSelect">
                <option value="">Select Division</option>
                <?php

                $stmt = $pdo->prepare("SELECT DISTINCT division FROM divisions");
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($result as $r) {
                    echo "<option value=" . "$r[division]" . ">" . $r['division'] . "</option>";
                }
                ?>

            </select>


            <div class="branches">

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Branch Name</th>
                                <th>Address</th>
                                <th>Divison</th>
                                <th>District</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>



    <?php include 'footer.php' ?>
    <script src="locations.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- <script src="script.js"></script> -->



</body>

</html>