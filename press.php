<?php
session_start();
include 'backend/Database/db.php';

$press_items = [];

$database = new Db();
$conn = $database->connect();

$sql = "SELECT * FROM blogs";
$stmt = $conn->prepare($sql);
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($res as $item) {
    $press_items[] = $item;
}

$article_id = isset($_GET['article']) ? (int)$_GET['article'] : null;
$current_article = null;

if ($article_id !== null && isset($press_items[$article_id])) {
    $current_article = $press_items[$article_id];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Press - ATMABISWAS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="press.css">
    <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
</head>



<body>
    <?php if (!$current_article): ?>
        <!-- Navbar CSS -->
        <link rel="stylesheet" href="navbar.css">
        <link rel="stylesheet" href="menutoggle.css">
        <link rel="stylesheet" href="sidebar.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
            integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Desktop Navbar -->
        <div class="navbar desktop-only">
            <div class="top-row">
                <div class="logo"><a href="index.php"><img src="logoBg.png" loading="lazy" alt=""></a></div>
                <div class="bars">
                    <a href="notice.php">Notice</a>
                    <a target="_blank" href="career.php">Career</a>
                    <a href="press.php">Press</a>
                    <a href="aboutus.php">About Us</a>
                </div>
            </div>
            <div class="bottom-row">
                <a href="index.php">Who we are</a>
                <div class="dropdown">
                    <div class="maindrop">
                        <a href="#">Our Team <span class="space"> </span> <i id="arrow" class="fa-solid fa-caret-down"></i></a>
                    </div>
                    <div class="dropdown-content">
                        <a href="eve.php">Executive</a>
                        <a href="generalbody.php">General Body</a>
                        <a href="SeniorManagement.php">Senior Management</a>
                        <a href="founder.php">Founder</a>
                    </div>
                </div>
                <div class="dropdown">
                    <div class="maindrop">
                        <a href="#">What we do <span> </span> <i id="arrow" class="fa-solid fa-caret-down"></i></a>
                    </div>
                    <div class="dropdown-content">
                        <a href="Green_Energy.php">Green Energy</a>
                        <a href="enterprice.php">Enterprise Development</a>
                        <a href="Agritural.php">Food & Agriculture</a>
                        <a href="readytoeat.php">Ready To Eat</a>
                        <a href="health.php">Health & Nutrition</a>
                    </div>
                </div>
                <a href="Events.php">Events</a>
                <a href="social.php">Social</a>
                <a href="contact.php">Contacts</a>
                <?php
                if (isset($_SESSION['username'])) {
                    echo '<a style="border:2px solid #007bff;" href="backend/DashBoard/dashboard.php">DashBoard</a>';
                } else {
                    echo '<a style="border:2px solid #007bff;" href="backend/login/prelogin.php">Login</a>';
                }
                ?>
            </div>
        </div>

        <!-- Mobile Header -->
        <div class="mobile-header mobile-only">
            <div class="logo"><a href="index.php"><img src="logoBg.png" loading="lazy" alt=""></a></div>
            <div class="menu-toggle" id="menu-toggleId">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>

        <!-- Mobile Sidebar -->
        <div class="sidenav">
            <div class="sidelogo">
                <img src="LOGO/Monogram for web only.png" loading="lazy" alt="Logo" class="profile-img">
                <i id="close-btn" class="fa-solid fa-times"></i>
            </div>
            <a href="index.php"><i class="fa-solid fa-house-user"></i> Who we are</a>
            <div class="sidedrop">
                <div class="mainsidedrop">
                    <a href="#"><i class="fa-solid fa-people-group"></i> Our Team <i id="arrow"
                            class="fa-solid fa-caret-down"></i></a>
                </div>
                <div class="sidedropContent">
                    <a href="eve.php"><i class="fa-solid fa-user-tie"></i> Executive</a>
                    <a href="generalbody.php"><i class="fa-solid fa-users"></i> General Body</a>
                    <a href="SeniorManagement.php"><i class="fa-solid fa-user-shield"></i> Senior Management</a>
                    <a href="founder.php"><i class="fa-solid fa-user"></i> Founder</a>
                </div>
            </div>
            <div class="sidedrop">
                <div class="mainsidedrop">
                    <a href="#"><i class="fa-solid fa-clipboard-list"></i> What we do <i id="arrow"
                            class="fa-solid fa-caret-down"></i></a>
                </div>
                <div class="sidedropContent">
                    <a href="Green_Energy.php"><i class="fa-solid fa-leaf"></i> Green Energy</a>
                    <a href="enterprice.php"><i class="fa-solid fa-building"></i> Enterprise Development</a>
                    <a href="Agritural.php"><i class="fa-solid fa-seedling"></i> Food & Agriculture</a>
                    <a href="readytoeat.php"><i class="fa-solid fa-utensils"></i> Ready To Eat</a>
                    <a href="health.php"><i class="fa-solid fa-heartbeat"></i> Health & Nutrition</a>
                </div>
            </div>
            <a href="Events.php"><i class="fa-solid fa-calendar"></i> Events</a>
            <a href="social.php"><i class="fa-solid fa-share-alt"></i> Social</a>
            <a href="contact.php"><i class="fa-solid fa-address-book"></i> Contacts</a>
            <?php
            if (isset($_SESSION['username'])) {
                echo '<a style="border:2px solid #007bff;" href="backend/DashBoard/dashboard.php"><i class="fa-solid fa-tachometer-alt"></i> DashBoard</a>';
            } else {
                echo '<a style="border:2px solid #007bff;" href="backend/login/prelogin.php"><i class="fa-solid fa-sign-in-alt"></i> Login</a>';
            }
            ?>
        </div>

        <header>
            <h1>Press & Media – ATMABISWAS</h1>
            <p>Showcasing our work through national and regional media—covering our impact, initiatives, and stories that inspire social transformation.</p>
        </header>
    <?php endif; ?>
    <div class="container">

        <?php if ($current_article): ?>
            <!-- Single Article View -->
            <div class="press-article-card">
                <a href="?" class="back-button">
                    <i class="fas fa-arrow-left"></i> Back to Press Coverage
                </a>
                <div class="article-header">
                    <h1 class="article-title"><?php echo $current_article['blog_title']; ?></h1>
                    <div class="article-meta">
                        <span><?php echo explode(" ", $current_article["upload_date"])[0]; ?></span>
                        <span>|</span>
                        <span><i class="fas fa-newspaper"></i> <?php echo $current_article['blog_author']; ?></span>
                    </div>
                </div>
                <!-- YouTube Video Embed or Image Display -->
                <?php
                $videoId = '';
                if (!empty($current_article['source_link'])) {
                    $youtubeLink = $current_article['source_link'];
                    if (strpos($youtubeLink, 'youtu.be') !== false) {
                        $parts = explode('/', parse_url($youtubeLink, PHP_URL_PATH));
                        $videoId = end($parts);
                    } else {
                        parse_str(parse_url($youtubeLink, PHP_URL_QUERY), $ytParams);
                        $videoId = $ytParams['v'] ?? '';
                    }
                }
                if (!empty($videoId)): ?>
                    <div class="article-video">
                        <iframe src="https://www.youtube.com/embed/<?php echo htmlspecialchars($videoId); ?>"
                            allowfullscreen></iframe>
                        <h1><?php echo htmlspecialchars($current_article['image_title'] ?? ''); ?></h1>
                    </div>
                <?php elseif (!empty($current_article['cover_img'])): ?>
                    <div class="article-banner">
                        <img src="<?php echo htmlspecialchars($current_article['cover_img']); ?>" alt="Cover Image">
                    </div>
                <?php else: ?>
                    <div style="padding: 20px; background-color: #f8f9fa; color: #555; border: 1px dashed #ccc; text-align: center; border-radius: 6px;">
                        No image has been uploaded.
                    </div>
                <?php endif; ?>
                <div class="article-content">
                    <?php echo $current_article['blog_content']; ?>
                </div>
                <!-- Source Link -->
                <?php if (!empty($current_article['source_link'])): ?>
                    <div class="article-source">
                        Source: <a href="<?php echo $current_article['source_link']; ?>" target="_blank" rel="noopener noreferrer">
                            <?php echo parse_url($current_article['source_link'], PHP_URL_HOST); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>


        <?php else: ?>


            <div class="filters">
                <button class="filter-btn active" data-year="all">All Coverage</button>
                <button class="filter-btn" data-year="2025">2025</button>
                <button class="filter-btn" data-year="2024">2024</button>
                <button class="filter-btn" data-year="2023">2023</button>
            </div>

            <div class="press-grid">
                <?php if (empty($press_items)): ?>
                    <div class="empty-state">
                        <i class="far fa-newspaper"></i>
                        <h3>No press coverage yet</h3>
                        <p>Check back later for updates on our media appearances.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($press_items as $id => $item): ?>
                        <a href="?article=<?php echo $id; ?>" class="press-card-link">
                            <div class="press-card" data-year="<?php echo $item['year']; ?>">

                                <div class="card-image">
                                    <?php
                                    if (!empty($item["cover_img"])) {
                                        $imgPath = $item["cover_img"];

                                        echo '<img src="' . $imgPath . '" alt="' . htmlspecialchars($item["blog_title"]) . '" style="max-width:100%; height:100%;">';
                                    } else {
                                        echo '<div style="padding: 10px; background-color: #f2f2f2; color: #555; border: 1px dashed #ccc; text-align: center; border-radius: 4px;">
        No image has been uploaded for this News.
    </div>';
                                    }
                                    ?>


                                </div>
                                <div class="card-content">
                                    <span class="press-date"><?php echo explode(" ", $item["upload_date"])[0]; ?></span>
                                    <h3 class="press-title"><?php echo $item['blog_title']; ?></h3>
                                    <div class="press-source">
                                        <i class="fas fa-newspaper"></i>
                                        <span><?php echo $item['blog_author']; ?></span>
                                    </div>
                                    <p class="press-summary"><?php
                                                                $summary = $item['summary'];
                                                                $words = explode(' ', $summary);
                                                                if (count($words) > 20) {
                                                                    $truncated = array_slice($words, 0, 20);
                                                                    echo implode(' ', $truncated) . '...';
                                                                } else {
                                                                    echo $summary;
                                                                }
                                                                ?></p>

                                    <div class="press-actions">
                                        <?php if (isset($_SESSION['username'])): ?>
                                            <a class="press-button update" href="../backend/DashBoard/update_Blog_Image.php?id=<?php echo $item['blog_id']; ?>" class="press-button update">
                                                <i style="margin-right:2px;" class="fas fa-sync-alt"></i> Update
                                            </a>
                                        <?php endif; ?>
                                        <button class="press-button read-more">
                                            Read More <i class="fas fa-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            <?php if (!$current_article): ?>
                // Filter functionality
                const filterButtons = document.querySelectorAll('.filter-btn');
                const pressCards = document.querySelectorAll('.press-card');

                filterButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        filterButtons.forEach(btn => btn.classList.remove('active'));
                        button.classList.add('active');

                        const year = button.dataset.year;

                        pressCards.forEach(card => {
                            card.style.display = (year === 'all' || card.dataset.year === year) ? 'block' : 'none';
                        });
                    });
                });

                // Animation observer
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('show');
                        }
                    });
                }, {
                    threshold: 0.1
                });

                document.querySelectorAll('.press-card').forEach(card => {
                    observer.observe(card);
                });
            <?php endif; ?>
        });
    </script>
</body>

</html>