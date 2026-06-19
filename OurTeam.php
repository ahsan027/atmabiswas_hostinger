<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Our Team - ATMABISWAS</title>
  <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
  <?php include 'seo.php'; ?>
  <style>
    body {
      font-family: "Times New Roman", Times, serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      color: #333;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .profile-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    .profile {
      border: 1px solid #ddd;
      padding: 20px;
      border-radius: 10px;
      background-color: #fff;
      text-align: center;
      transition: box-shadow 0.3s ease;
    }

    .profile:hover {
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .profile img {
      border-radius: 15px;
      /* Adjust the radius value for more or less rounded corners */
      width: 300px;
      height: 300px;
      object-fit: cover;
    }

    .profile h2 {
      font-size: 1.2em;
      margin: 10px 0;
      color: #005B96;
      margin-right: 3px;
    }

    .profile-row .profile {
      margin-bottom: 20px;
      /* Add this line for gap between rows */
    }

    .profile {
      margin-bottom: 20px;
      /* Add this line for gap between divs */
    }

    .profile p {
      font-size: 1em;
      color: #0077C2;
      margin-right: 3px;
    }

    .profile-row {
      display: flex;
      flex-direction: column;
    }

    .profile-row .profile {
      display: flex;
      align-items: center;
    }

    .profile-row .profile img {
      margin-right: 20px;
    }

    .profile-row .profile:nth-child(2) {
      flex-direction: row-reverse;
    }

    /* Responsive styling */
    @media (max-width: 1024px) {
      .profile-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 768px) {
      .profile-grid {
        grid-template-columns: 1fr;
      }

      .profile img {
        width: 150px;
        height: 150px;

      }
    }


  </style>
</head>

<body>
  <?php include 'Navbar.php'; ?>
  <div class="container">
    <h1>Our Team</h1>
    <h2>Senior Management</h2>
    <div class="profile-row">
      <div class="profile">
        <img src="https://t4.ftcdn.net/jpg/07/08/47/75/360_F_708477508_DNkzRIsNFgibgCJ6KoTgJjjRZNJD4mb4.jpg" alt="Syed Ferhat Anwar">
        <div>
          <h2>Professor Syed Ferhat Anwar, PhD</h2>
          <p>Vice-Chancellor, BRAC University</p>
        </div>
      </div>
      <div class="profile">
        <img src="https://t4.ftcdn.net/jpg/07/08/47/75/360_F_708477508_DNkzRIsNFgibgCJ6KoTgJjjRZNJD4mb4.jpg" alt="Syed Mahfuzul Aziz">
        <div class="text">
          <h2>Professor Syed Mahfuzul Aziz, PhD</h2>
          <p>Pro Vice-Chancellor, BRAC University</p>
        </div>
      </div>
    </div>

    <section class="profile-grid">
      <div class="profile"> <img src="https://t4.ftcdn.net/jpg/07/08/47/75/360_F_708477508_DNkzRIsNFgibgCJ6KoTgJjjRZNJD4mb4.jpg" alt="Mohammad Mujibul Haque">
        <h2>Professor Mohammad Mujibul Haque, PhD</h2>
        <p>Acting Dean, BRAC Business School, BRAC University</p>
      </div>
      <div class="profile"> <img src="https://t4.ftcdn.net/jpg/07/08/47/75/360_F_708477508_DNkzRIsNFgibgCJ6KoTgJjjRZNJD4mb4.jpg" alt="Mahbubul Alam Majumdar">
        <h2>Professor Mahbubul Alam Majumdar, PhD</h2>
        <p>Dean, School of Data and Sciences, BRAC University</p>
      </div>
      <div class="profile"> <img src="https://t4.ftcdn.net/jpg/07/08/47/75/360_F_708477508_DNkzRIsNFgibgCJ6KoTgJjjRZNJD4mb4.jpg" alt="Samia Huq">
        <h2>Professor Samia Huq, PhD</h2>
        <p>Dean, School of General Education, BRAC University</p>
      </div>
    </section>
    <h2>Head of Schools</h2>
    <section class="profile-grid">
      <div class="profile"> <img src="https://t4.ftcdn.net/jpg/07/08/47/75/360_F_708477508_DNkzRIsNFgibgCJ6KoTgJjjRZNJD4mb4.jpg" alt="Mohammad Mujibul Haque">
        <h2>Professor Mohammad Mujibul Haque, PhD</h2>
        <p>Acting Dean, BRAC Business School, BRAC University</p>
      </div>
      <div class="profile"> <img src="https://t4.ftcdn.net/jpg/07/08/47/75/360_F_708477508_DNkzRIsNFgibgCJ6KoTgJjjRZNJD4mb4.jpg" alt="Mahbubul Alam Majumdar">
        <h2>Professor Mahbubul Alam Majumdar, PhD</h2>
        <p>Dean, School of Data and Sciences, BRAC University</p>
      </div>
      <div class="profile"> <img src="https://t4.ftcdn.net/jpg/07/08/47/75/360_F_708477508_DNkzRIsNFgibgCJ6KoTgJjjRZNJD4mb4.jpg" alt="Samia Huq">
        <h2>Professor Samia Huq, PhD</h2>
        <p>Dean, School of General Education, BRAC University</p>
      </div>
    </section>
  </div>
  <?php include 'footer.php'; ?>
</body>

</html>