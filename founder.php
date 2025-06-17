<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Founder - ATMABISWAS </title>
    
    <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;


        }

        body {
            background-color: #e3f2fd;
            color: #333;
            overflow-x: hidden;
        }

        header {
            text-align: center;
            background: linear-gradient(90deg, #0d47a1, #1976d2);
            color: white;
            padding: 20px 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        header p {
            font-size: 1.3rem;
            line-height: 1.6;
            opacity: 0.9;
        }

        .founder-section {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 80px 20px;
        }

        .founder-card {
            background: white;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            border-radius: 25px;
            overflow: hidden;
            max-width: 1200px;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease-in-out;
        }

        .founder-card:hover {
            transform: scale(1.03);
        }

        .founder-card img {
            width: 100%;
            max-width: 450px;
            height: auto;
            object-fit: cover;
            border-radius: 25px 0 0 25px;
        }

        .founder-details {
            flex: 2;
            padding: 50px;
            background: paperwhite;
            border-radius: 0 25px 25px 0;
        }

        .founder-details h2 {
            font-size: 2.2rem;
            color: #0d47a1;
            margin-bottom: 15px;
        }

        .founder-details p {
            margin: 15px 0;
            font-size: 1.2rem;
            line-height: 1.8;
            color: #333;
        }

        @media (max-width: 768px) {
            .founder-card {
                flex-direction: column;
                text-align: center;
            }
            .founder-card img {
                max-width: 100%;
                border-radius: 25px 25px 0 0;
            }
            .founder-details {
                border-radius: 0 0 25px 25px;
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <?php include 'Navbar.php'; ?>
    <header>
        <h1>Founder of ATMABISWAS</h1>
        <p>Guiding ATMABISWAS Towards Harmony and Hope</p>
    </header>

    <div class="founder-section">
        <div class="founder-card">
            <img src="photos/ED_sir.jpg" alt="Akramul Haque Biswas">
            <div class="founder-details">
                <h2>Akramul Haque Biswas</h2>
                <p>Founder & Executive Director</p>
                <br>
                <p>Akramul Haque Biswas is the founder and Executive Director of ATMABISWAS. He has been actively working to address poverty, inequality and environmental sustainability, focusing on solutions that create long-term impact. His efforts center around sustainable development, gender justice and green energy, ensuring that communities have the resources and opportunities to grow while maintaining ecological balance.
                
                Through ATMABISWAS, he has led various initiatives that promote renewable energy, economic empowerment, and social justice, aiming to build a society where everyone has equal access to opportunities. His work emphasizes collaboration and community-driven solutions, believing that progress comes from collective effort and sustainable practices. With a commitment to equity, environmental protection and progressive social transformation, he continues to advocate for a more just and sustainable future.</p>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
