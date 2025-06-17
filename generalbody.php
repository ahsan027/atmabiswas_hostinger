<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Body - ATMABISWAS </title>
    
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
        max-width: 1200px;
        margin: 0 auto 30px auto;
    }

    .oth {
        display: flex;
        gap: 20px;
        max-width: 1200px;
        margin: 0 auto;
        justify-content: center;
        align-items: flex-start;
    }

    .oth .executive-card {
        flex-grow: 1;
        flex-shrink: 1;
        max-width: 500px;
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
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

    .othermembers {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        justify-content: center;
        max-width: 1200px;
        margin: 0 auto;
    }

    .card {
        background: white;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
    }

    .cardhead1,
    .cardhead2 {
        display: flex;
        justify-content: space-around;
        align-items: center;
        background-color: white;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        border-radius: 15px;
        overflow: hidden;
        width: 100%;
        padding: 10px;
    }

    .cardhead1 img,
    .cardhead2 img {
        width: 20%;
        max-height: 300px;
        object-fit: contain;
        background-color: #fff;
        padding: 10px;
    }

    .card img {
        width: 100%;
        height: auto;
        max-height: 300px;
        object-fit: contain;
        background-color: #fff;
        padding: 10px;
        border-bottom: 3px solid #0a58ca;
    }

    .card-body,
    .card-body1,
    .card-body2 {
        padding: 20px;
        text-align: center;
        background-color: #fff;
    }

    .card-body h2 {
        font-size: 1.5rem;
        color: #0a58ca;
        font-weight: 600;
    }

    .card-body h3 {
        font-size: 1.1rem;
        color: #666;
        font-weight: 500;
    }

    .card-body p {
        font-size: 1rem;
        line-height: 1.6;
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

        .card {
            width: 100%;
        }

        .cardhead1 img,
        .cardhead2 img {
            width: 30%;
        }

        .othermembers {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
    }
    </style>
</head>

<body>
    <?php include 'Navbar.php' ?>
    <header>
        <h1>Executive Committee of ATMABISWAS NGO</h1>
        <p>Empowering communities and driving sustainable development </p>
    </header>

    <div class="container">
        <section class="executive-section">
            <div class="mdsir">
                <div class="executive-card">
                    <img src="photos/Salma_Asif.png" alt="Mises Salma Asif">
                    <h2>Miss Salma Asif</h2>
                    <p><strong>President</strong></p>
                    <p>A visionary leader since 1991, guiding ATMABISWAS with commitment to social empowerment.</p>
                </div>

            </div>


            <div class="oth">
                <div class="executive-card">
                    <img src="photos/AfrozaBegum.png" alt="Mst. Afroza Begum" style="object-position: top;">
                    <h2>Mst. Afroza Begum</h2>
                    <p><strong>Vice President</strong></p>
                    <p>Bringing extensive experience in social work to support strategic growth since 1991.</p>
                </div>

                <div class="executive-card">
                    <img src="photos/edsir.png" alt="Md. Akramul Haque Biswas">
                    <h2>Md. Akramul Haque Biswas</h2>
                    <p><strong>Secretary / Executive Director</strong></p>
                    <p>Ensuring effective project implementation and leadership at ATMABISWAS.</p>
                </div>

            </div>


        </section>

        <div class="othermembers">
            <div class="card">
                <img src="photos/ranabiswas.png" alt="Md. Iktiar Uddin">
                <div class="card-body">
                    <h2>Md. Iktiar Uddin</h2>
                    <h3>Treasurer</h3>
                </div>
            </div>

            <div class="card">
                <img src="photos/nazma.png" alt="Nazma Shaheen">
                <div class="card-body">
                    <h2>Nazma Shaheen</h2>
                    <h3>Executive Member</h3>
                </div>
            </div>

            <div class="card">
                <img src="photos/Shahana.png" alt="Mst. Shahana Pervin">
                <div class="card-body">
                    <h2>Mst. Shahana Pervin</h2>
                    <h3>Executive Member</h3>
                    
                </div>
            </div>

            <div class="card">
                <img src="photos/alo.png" alt="Md. Nazrul Islam Alo">
                <div class="card-body">
                    <h2>Md. Nazrul Islam Alo</h2>
                    <h3>Executive Member</h3>
                    
                </div>
            </div>
            <div class="card">
                <img src="generalbody/asadulbiswas.jpg" alt="Md. Asadul Haque Biswas">
                <div class="card-body">
                    <h2>Md. Asadul Haque Biswas</h2>
                    <h3>Member</h3>
                   
                </div>
            </div>
            <div class="card">
                <img src="generalbody/malaka.png" alt="Mst.Malaka Parvin">
                <div class="card-body">
                    <h2>Mst. Malaka Parvin</h2>
                    <h3>Member</h3>
                </div>
            </div>
            <div class="card">
                <img src="generalbody/vola.png" alt="Obaidul Haque Bhola">
                <div class="card-body">
                    <h2>Obaidul Haque Bhola</h2>
                    <h3>Member</h3>
                </div>
            </div> 
            <div class="card">
                <img src="generalbody/murtoza.png" alt="Salahuddin Mohammad Mortaza">
                <div class="card-body">
                    <h2>Salahuddin Mohammad Mortaza</h2>
                    <h3>Member</h3>
                </div>
            </div>
            <div class="card">
                <img src="generalbody/zaharul.png" alt="Md. Zahurul Islam Joarddar">
                <div class="card-body">
                    <h2>Md. Zahurul Islam Joarddarn</h2>
                    <h3>Member</h3>
                </div>
            </div>
            <div class="card">
                <img src="generalbody/malik.png" alt="Md. Humayun Kabir Malik">
                <div class="card-body">
                    <h2>Md. Humayun Kabir Malik</h2>
                    <h3> Member</h3>
                </div>
            </div>
            <div class="card">
                <img src="generalbody/manik.png" alt="Md. Aman Ullah">
                <div class="card-body">
                    <h2>Md. Aman Ullah</h2>
                    <h3>Member</h3>
                </div>
            </div>
            <div class="card">
                <img src="generalbody/billal.png" alt="Advocate Md. Belal Hossain">
                <div class="card-body">
                    <h2>Advocate Md. Belal Hossain</h2>
                    <h3>Member</h3>
                </div>
            </div>     

            <div class="card">
                <img src="generalbody/rasel.png" alt="Md. Amirul Haque (Rasel)">
                <div class="card-body">
                    <h2>Md. Amirul Haque Rasel</h2>
                    <h3>Member</h3>
                   
                </div>
            </div>

            <div class="card">
                <img src="LOGO/NGO_logo_monogram.png" alt="Salauddin Biswas Mona">
                <div class="card-body">
                    <h2>Salauddin Biswas Mona</h2>
                    <h3>Member</h3>
                   
                </div>
            </div>

            <div class="card">
                <img src="LOGO/NGO_logo_monogram.png" alt="Md. Rizik Biswas">
                <div class="card-body">
                    <h2>Md. Rizik Biswas</h2>
                    <h3>Member</h3>
                   
                </div>
            </div>
            <div class="card">
                <img src="LOGO/NGO_logo_monogram.png" alt="Md. Tipu Sultan Biswas">
                <div class="card-body">
                    <h2>Md. Tipu Sultan Biswas</h2>
                    <h3>Member</h3>
                   
                </div>
            </div>
            <div class="card">
                <img src="LOGO/NGO_logo_monogram.png" alt="Md. Milton Biswas">
                <div class="card-body">
                    <h2>Md. Milton Biswas</h2>
                    <h3>Member</h3>
                   
                </div>
            </div>

            <div class="card">
                <img src="LOGO/NGO_logo_monogram.png" alt="Md. Shahin Biswas">
                <div class="card-body">
                    <h2>Md. Shahin Biswas</h2>
                    <h3>Member</h3>
                   
                </div>
            </div>



        </div>
    </div>
    <?php include 'footer.php' ?>
</body>

</html>