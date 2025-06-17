<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Senior Management - ATMABISWAS </title>
  
    <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {

      background-color: #f4f9ff;
      overflow-x: hidden;

    }

    .container {
      max-width: 100vw;
      margin: 40px auto;
      padding: 20px;
    }

    header {
      text-align: center;
      background: linear-gradient(90deg, #0a58ca, #176cc6);
      color: white;
      padding: 50px 20px;

    }

    header h1 {
      margin: 0;
      font-size: 1.8rem;
      font-weight: 700;
      color: white;
    }

    header p {
      margin: 10px 0;
      font-size: 1.2rem;
      opacity: 0.9;
    }

    h1 {
      color: #005B96;
      font-size: 3em;
      text-align: center;
      margin-bottom: 20px;
    }

    /* Unique Styling for Top Management */
    .executive-section {
      display: flex;
      flex-direction: column;
      flex-wrap: wrap;
      align-items: center;
      gap: 20px;
      justify-content: center;
      margin-bottom: 30px;
    }

    .oth {
      display: flex;
      gap: 20px;
    }

    .executive-card {
      background: #ffffff;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
      width: 90%;
      max-width: 500px;
    }

    .executive-card img {
      width: 180px;
      height: 180px;
      border-radius: 50%;
      border: 4px solid #005B96;
      object-fit: cover;
    }

    .executive-card h2 {
      font-size: 1.5em;
      color: #005B96;
      margin: 15px 0 5px;
    }

    .executive-card p {
      font-size: 1em;
      color: #333;
      line-height: 1.6;
    }

    /* Standard Grid for Other Staff */
    .profile-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      justify-content: center;
    }

    .profile {
      background: #fff;
      border-radius: 12px;
      padding: 15px;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }

    .profile:hover {
      transform: translateY(-5px);
    }

    .profile img {
      width: 140px;
      height: 140px;
      border-radius: 12px;
      object-fit: cover;
      border: 3px solid #005B96;
    }

    .profile h2 {
      font-size: 1.2em;
      color: #005B96;
      margin: 10px 0 5px;
    }

    .profile p {
      font-size: 1em;
      color: #444;
      font-weight: bold;
    }

    @media (max-width: 768px) {
      .executive-card {
        width: 100%;
      }

      .profile-grid {
        grid-template-columns: repeat(1, minmax(200px, 1fr));
      }

      .oth {
        flex-direction: column;
      }
    }
  </style>
</head>

<body>
  <?php include 'Navbar.php' ?>
  <header>

    <h1>Empowering Communities And Driving Sustainable Development </h1>
  </header>

  <div class="container">
    <h1>Senior Management</h1>
    <section class="executive-section">
      <div class="mdsir">
        <div class="executive-card">
          <img src="photos/edsir.png" alt="Akramul Haque Biswas">
          <h2>Akramul Haque Biswas</h2>
          <p><strong>Executive Director, ATMABISWAS</strong></p>
          <p>The founder and Executive Director of ATMABISWAS, Akramul Haque Biswas has dedicated his life to creating a world where justice, equality, and environmental harmony are realities for all. His leadership and vision continue to inspire and empower communities through sustainable development and advocacy.</p>
        </div>

      </div>


      <div class="oth">
        <div class="executive-card">
          <img src="generalbody/malaka.png" alt="Malaka Parvin">
          <h2>Malaka Parvin </h2>
          <p><strong>Deputy Executive Director, ATMABISWAS</strong></p>
          <p>The founder and Deputy Executive Director of ATMABISWAS, Malaka Parvin has dedicated his life to creating a world where justice, equality, and environmental harmony are realities for all. His leadership and vision continue to inspire and empower communities through sustainable development and advocacy.</p>
        </div>

        <div class="executive-card">
          <img src="photos/ddsir.png" alt="Rafiqul Hasan Joarder">
          <h2>Rafiqul Hasan Joarder</h2>
          <p><strong>Director, ATMABISWAS</strong></p>
          <p>Rafiqul Hasan Joarder plays a key role in strategic planning, operational management, and policy implementation at ATMABISWAS. His dedication to sustainable development and financial inclusion has helped countless individuals and communities thrive.</p>
        </div>

      </div>


    </section>
    <h1>Assistant Director (Microfinance)</h1>
    <section class="profile-grid">
      <div class="profile"> <img src="photos/Akkas_PP.jpg" alt="Akkas Ali">
        <h2>Akkas Ali</h2>
        <p>Assistant Director</p>
      </div>
      <div class="profile"> <img src="photos/Hasan_PP.jpg" alt="MD. Hassanur Jamman">
        <h2>MD. Hassanur Jamman</h2>
        <p>Assistant Director</p>
      </div>
      <div class="profile"> <img src="photos/rimusir.png" alt="Rimu Sir">
        <h2>MD: Abu Sadat Rimu</h2>
        <p>Assistant Director</p>
      </div>
    </section>
    <br>
    <br>


    <!-- <h1>Accounts Officers</h1>
    <section class="profile-grid">
      <div class="profile"> <img src="photos/kumkum.png" alt="Mst.Nargis Parvin">
        <h2>Mst. Nargis Parvin</h2>
        <p>Accounts Officer</p>
      </div>
      <div class="profile"> <img src="photos/rita.png" alt="Rita">
        <h2>Mst. Sharmin Sultana (Rita)</h2>
        <p>Accounts Officer</p>
      </div>
      <div class="profile"> <img src="photos/hena.png" alt="Jesmin Ara Hena">
        <h2>Mst. Jesmin Ara Hena</h2>
        <p>Accounts Officer</p>
      </div>
    </section>
    <br>
    <br>


    <h1>Managers / Officers</h1>
    <section class="profile-grid">
      <div class="profile"> <img src="photos/Zahangir.JPG" alt="Zahangir">
        <h2>Md.Zahangir Alom</h2>
        <p>Manager (MIS)</p>
      </div>

      <div class="profile"> <img src="photos/Jinnah.png" alt="Abu Mohammad Jinnah">
        <h2>Abu Mohammad Jinnah</h2>
        <p>Co-Manager (MIS)</p>
      </div>
      <div class="profile"> <img src="photos/Sharmin.png" alt="Sharmin Aktar">
        <h2>Mst. Sharmin Aktar</h2>
        <p>Officer (MIS)</p>
      </div>

    </section>
    <br>
    <br>

    <h1>Other Officers</h1>
    <section class="profile-grid">
      <div class="profile"> <img src="photos/Firoze.png" alt="Md. Firoze Biswas">
        <h2>Md. Firoze Biswas</h2>
        <p>Branch Manager</p>
      </div>
      <div class="profile"> <img src="photos/MD.SHAH ALAM ALO-IT OFFICER, PIN-481.jpg" alt="Shah Alam Alo">
        <h2>Md. Shah Alam Alo</h2>
        <p>Assistant HR Officer</p>
      </div>
      <div class="profile"> <img src="photos/roksona.png" alt="Roksana Parvin">
        <h2>Mst. Roksana Parvin</h2>
        <p>Computer Operator</p>
      </div>
      <div class="profile"> <img src="photos/rana.png" alt="Yatab Ali Rana">
        <h2>Md. Yatab Ali Rana</h2>
        <p>Assistant Legal Coordinator</p>
      </div>
      <div class="profile"> <img src="photos/arongo.png" alt="Arongo Biswas">
        <h2>Md. Arongo Biswas</h2>
        <p>Assistant Administrative Officer</p>
      </div>
      <div class="profile"> <img src="photos/shanin.png" alt="Shahin Akter">
        <h2>Mst. Shahin Akter</h2>
        <p>Receptionist</p>
      </div>
    </section> -->


  </div>
  <?php include 'footer.php' ?>
</body>

</html>