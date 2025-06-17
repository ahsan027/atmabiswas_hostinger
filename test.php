<?php 
    include 'backend/Database/db.php';

    $database = new Db();

    $conn = $database->connect();

    $sql = "SELECT * FROM img_upload ORDER BY uploaded_on DESC LIMIT 6";

    $stmt = $conn->prepare($sql);

    $stmt->execute();

    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<script src='main.js'></script>

<div class="card-container">
    <?php
if (count($photos) > 0) {
    foreach ($photos as $photo) {
        
        $imgPath = isset($photo["img_path"]) ? $photo["img_path"] : "LOGO/NGO_logo_monogram.png";
        
       
        $title = htmlspecialchars($photo["img_title"]);
        $description = htmlspecialchars($photo["img_description"]);

        echo '
        <div class="card">
            <img src="' . $imgPath . '" alt="'.$title.'">
            <div class="card-content">
                <h3>' . $title . '</h3>
                <p class="card-text">' . $description . '</p>
            </div>
        </div>';
    }
} else {
   echo '<p style="text-align: center; font-size: 1.2rem; color: #666; background-color: #f2f2f2;
    padding: 15px;
    border-radius: 8px;
    margin: 20px auto;
    width: fit-content;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
">No Latest News Currently</p>';

}
?>

</div>

<style>
.card-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    justify-content: center;
    align-items: stretch;
    place-items: center;
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.card {
    position: relative;
    width: 100%;
    max-width: 380px;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    cursor: pointer;
    transition: transform 0.4s ease-in-out, box-shadow 0.4s ease-in-out;
    background: #ffffff;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.card:hover {
    transform: translateY(-12px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.card img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: 15px 15px 0 0;
}

.card-content {
    text-align: center;
    padding: 25px;
    background-color: #ffffff;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.card h3 {
    margin: 0 0 12px 0;
    color: #333;
    font-size: 1.4em;
    font-weight: 700;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.card-text {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    font-size: 1em;
    padding: 20px;
    box-sizing: border-box;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
    text-align: center;
    line-height: 1.6;
}

.card:hover .card-text {
    opacity: 1;
}

/* Responsive Design */
@media (max-width: 768px) {
    .card-container {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .card img {
        height: 200px;
    }

    .card-content {
        padding: 15px;
    }

    .card h3 {
        font-size: 1.2em;
    }

    .card-text {
        font-size: 0.9em;
        padding: 15px;
    }
}

@media (max-width: 480px) {
    .card-container {
        grid-template-columns: repeat(1, 1fr);
        gap: 15px;
        padding: 0 10px;
    }

    .card {
        max-width: 100%;
    }

    .card img {
        height: 180px;
    }

    .card-content {
        padding: 10px;
    }

    .card h3 {
        font-size: 1.1em;
    }

    .card-text {
        font-size: 0.8em;
        padding: 10px;
    }
}
</style>

<script>
document.querySelectorAll('.card').forEach(card => {

    card.addEventListener('mouseenter', () => {
        card.querySelector('.card-text').style.opacity = '1';
    });

    card.addEventListener('mouseleave', () => {
        card.querySelector('.card-text').style.opacity = '0';
    });

});
</script>