<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Senior Management - ATMABISWAS </title>
  <style>
    body {
      font-family: Arial, sans-serif;
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
      border-radius: 15px; /* Adjust the radius value for more or less rounded corners */
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
  margin-bottom: 20px; /* Add this line for gap between rows */
}

.profile {
  margin-bottom: 20px; /* Add this line for gap between divs */
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
      .profile img{
        width: 150px;
        height: 150px;

      }
    }


    .navbar {
            display: flex;
            flex-direction: column;
            padding: 20px;
            background-color: #ffffff; /* White background for navbar */
            border-bottom: 2px solid #0073e6; /* Blue border at the bottom */
        }
        .navbar .top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
        }
        .navbar .top-row a {
            color: #0073e6; /* Blue color for links */
            text-decoration: none;
            margin: 0 10px;
            padding: 7px 10px;
            transition: background-color 0.3s, color 0.3s;
            border-radius: 15px;
        }
        .navbar .top-row a:hover {
            background-color: #0073e6; /* Blue hover effect */
            color: #ffffff;
        }
        .navbar .bottom-row {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            margin-top: 10px;
        }
        .navbar .bottom-row a {
            color: #0073e6;
            text-decoration: none;
            padding: 10px 15px;
            transition: background-color 0.3s, color 0.3s;
            border-radius: 15px;
        }
        .navbar .bottom-row a:hover {
            background-color: #0073e6;
            color: #ffffff;
        }

        .navbar .bottom-row a.active {
            background-color: #005bb5; /* Darker blue for active page */
            color: #ffffff;
        }
        .logo {
            font-size: 1.5rem;
            color: #0073e6;
            font-weight: bold;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 30px;
            background-color: #ffffff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            border-radius: 10px;
            width: 300px;
            max-width: 90%;
            opacity: 0;
            transition: opacity 0.3s, transform 0.3s;
        }
        .popup.active {
            display: block;
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
        .popup .close-btn {
            display: block;
            text-align: right;
            cursor: pointer;
            font-size: 1.2rem;
            color: #0073e6;
        }
        .popup form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .popup form input {
            padding: 10px;
            border: 1px solid #0073e6;
            border-radius: 5px;
        }
        .popup form button {
            padding: 10px;
            border: none;
            background-color: #0073e6;
            color: #ffffff;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .popup form button:hover {
            background-color: #005bb5;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .navbar .top-row {
                justify-content: space-between;
            }
            .navbar .top-row .menu-toggle {
                display: block;
                cursor: pointer;
                font-size: 1.5rem;
                color: #0073e6;
            }
            .navbar .top-row a {
                display: none;
            }
            .navbar .bottom-row {
                display: none;
                flex-direction: column;
            }
            .navbar .bottom-row.active {
                display: flex;
                gap: 1rem;
            }
        }

        @media (min-width: 769px) {
            .navbar .top-row .menu-toggle {
                display: none;
            }
            .navbar .top-row a {
                display: inline;
            }
        }
  </style>
</head>
<body>
      <div class="navbar">
        <div class="top-row">
            <div class="menu-toggle">&#9776;</div>
            <div class="logo">ATMA BISWAS</div>
            <div>
                <a href="#">Notice</a>
                <a href="#">Career</a>
                <a href="#">Press</a>
                <a href="#">Event</a>
            </div>
        </div>
        <div class="bottom-row">
            <a  href="/dashboard/atmabiswas">Who we are</a>
            <a href="#">What we do</a>

            <a class="active" href="Pages/OurTeam.php">Our team</a>
            <a  href="Contact.php">Contact</a>
            <a href="#" id="login-btn">Login</a>
        </div>
    </div>

    <div class="popup" id="login-popup">
        <div class="close-btn" id="close-popup">&times;</div>
        <form>
            <input type="text" placeholder="Username" required>
            <input type="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
  <div class="container">
    <h1>Senior Management</h1>
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
        <div ="text">
          <h2>Professor Syed Mahfuzul Aziz, PhD</h2>
          <p>Pro Vice-Chancellor, BRAC University</p>
        </div>
      </div>
    </div>

    <section class="profile-grid"> <div class="profile"> <img src="https://t4.ftcdn.net/jpg/07/08/47/75/360_F_708477508_DNkzRIsNFgibgCJ6KoTgJjjRZNJD4mb4.jpg" alt="Mohammad Mujibul Haque"> <h2>Professor Mohammad Mujibul Haque, PhD</h2> <p>Acting Dean, BRAC Business School, BRAC University</p> </div> <div class="profile"> <img src="https://t4.ftcdn.net/jpg/07/08/47/75/360_F_708477508_DNkzRIsNFgibgCJ6KoTgJjjRZNJD4mb4.jpg" alt="Mahbubul Alam Majumdar"> <h2>Professor Mahbubul Alam Majumdar, PhD</h2> <p>Dean, School of Data and Sciences, BRAC University</p> </div> <div class="profile"> <img src="https://t4.ftcdn.net/jpg/07/08/47/75/360_F_708477508_DNkzRIsNFgibgCJ6KoTgJjjRZNJD4mb4.jpg" alt="Samia Huq"> <h2>Professor Samia Huq, PhD</h2> <p>Dean, School of General Education, BRAC University</p> </div> </section>
<h1>Head of Schools</h1> <!-- Add this title --> <section class="profile-grid"> <div class="profile"> <img src="https://t4.ftcdn.net/jpg/07/08/47/75/360_F_708477508_DNkzRIsNFgibgCJ6KoTgJjjRZNJD4mb4.jpg" alt="Mohammad Mujibul Haque"> <h2>Professor Mohammad Mujibul Haque, PhD</h2> <p>Acting Dean, BRAC Business School, BRAC University</p> </div> <div class="profile"> <img src="https://t4.ftcdn.net/jpg/07/08/47/75/360_F_708477508_DNkzRIsNFgibgCJ6KoTgJjjRZNJD4mb4.jpg" alt="Mahbubul Alam Majumdar"> <h2>Professor Mahbubul Alam Majumdar, PhD</h2> <p>Dean, School of Data and Sciences, BRAC University</p> </div> <div class="profile"> <img src="https://t4.ftcdn.net/jpg/07/08/47/75/360_F_708477508_DNkzRIsNFgibgCJ6KoTgJjjRZNJD4mb4.jpg" alt="Samia Huq"> <h2>Professor Samia Huq, PhD</h2> <p>Dean, School of General Education, BRAC University</p> </div> </section>
  </div>
  <script>
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.bottom-row').classList.toggle('active');
        });

        document.getElementById('login-btn').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('login-popup').classList.add('active');
        });

        document.getElementById('close-popup').addEventListener('click', function() {
            document.getElementById('login-popup').classList.remove('active');
        });

        </script>
</body>
</html>
