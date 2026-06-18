<?php
session_start();
include 'config.php';
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
    <title><?= $current_article
        ? htmlspecialchars($current_article['blog_title']) . ' — ATMABISWAS'
        : 'Press &amp; Media Coverage — ATMABISWAS Bangladesh'
    ?></title>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-EZVV9DWWY7"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-EZVV9DWWY7');
    </script>
    <?php include 'seo.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="menutoggle.css">
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="press.css">
    <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
</head>

<body>
    <!-- Desktop Navbar -->
    <div class="navbar desktop-only">
        <div class="top-row">
            <div class="logo"><a href="index.php"><img src="logoBg.png" loading="lazy" alt="ATMABISWAS NGO"></a></div>
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
                    <a href="Agritural.php">Food &amp; Agriculture</a>
                    <a href="readytoeat.php">Ready To Eat</a>
                    <a href="health.php">Health &amp; Nutrition</a>
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
        <div class="logo"><a href="index.php"><img src="logoBg.png" loading="lazy" alt="ATMABISWAS NGO"></a></div>
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
                <a href="#"><i class="fa-solid fa-people-group"></i> Our Team <i id="arrow" class="fa-solid fa-caret-down"></i></a>
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
                <a href="#"><i class="fa-solid fa-clipboard-list"></i> What we do <i id="arrow" class="fa-solid fa-caret-down"></i></a>
            </div>
            <div class="sidedropContent">
                <a href="Green_Energy.php"><i class="fa-solid fa-leaf"></i> Green Energy</a>
                <a href="enterprice.php"><i class="fa-solid fa-building"></i> Enterprise Development</a>
                <a href="Agritural.php"><i class="fa-solid fa-seedling"></i> Food &amp; Agriculture</a>
                <a href="readytoeat.php"><i class="fa-solid fa-utensils"></i> Ready To Eat</a>
                <a href="health.php"><i class="fa-solid fa-heartbeat"></i> Health &amp; Nutrition</a>
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

    <?php if ($current_article): ?>
        <!-- Article Hero (title + meta + back button) -->
        <div class="pr-hero pr-article-hero">
            <a href="?" class="pr-back-btn">
                <i class="fas fa-arrow-left"></i> Back to Press Coverage
            </a>
            <i class="fas fa-newspaper pr-hero-icon"></i>
            <h1><?= htmlspecialchars($current_article['blog_title']) ?></h1>
            <div class="pr-article-meta">
                <span><i class="fas fa-calendar-alt"></i><?= htmlspecialchars(explode(' ', $current_article['upload_date'])[0]) ?></span>
                <span class="pr-meta-sep">|</span>
                <span><i class="fas fa-newspaper"></i><?= htmlspecialchars($current_article['blog_author']) ?></span>
            </div>
        </div>
    <?php else: ?>
        <!-- Press List Hero -->
        <div class="pr-hero">
            <i class="fas fa-newspaper pr-hero-icon"></i>
            <h1>Press &amp; Media Coverage</h1>
            <p>Showcasing our work through national and regional media — covering our impact, initiatives, and stories that inspire social transformation.</p>
        </div>
    <?php endif; ?>

    <div class="container">

        <?php if ($current_article): ?>
            <!-- Single Article View -->
            <div class="press-article-card">
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
                        <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($videoId) ?>"
                            allowfullscreen></iframe>
                        <?php if (!empty($current_article['image_title'])): ?>
                            <p class="article-video-caption"><?= htmlspecialchars($current_article['image_title']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php elseif (!empty($current_article['cover_img'])): ?>
                    <div class="article-banner">
                        <img src="<?= htmlspecialchars($current_article['cover_img']) ?>"
                             alt="<?= htmlspecialchars($current_article['blog_title']) ?>">
                    </div>
                <?php else: ?>
                    <div class="article-no-image">
                        <i class="fas fa-image"></i>
                        <span>No image available for this article</span>
                    </div>
                <?php endif; ?>

                <div class="article-content">
                    <?= $current_article['blog_content'] ?>
                </div>

                <?php if (!empty($current_article['source_link'])): ?>
                    <div class="article-source">
                        <i class="fas fa-external-link-alt"></i> Source:
                        <a href="<?= htmlspecialchars($current_article['source_link']) ?>"
                           target="_blank" rel="noopener noreferrer">
                            <?= htmlspecialchars(parse_url($current_article['source_link'], PHP_URL_HOST)) ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        <?php else: ?>

            <div class="filters">
                <button class="filter-btn active" data-year="all">
                    <i class="fas fa-layer-group"></i> All Coverage
                </button>
                <button class="filter-btn" data-year="2025">2025</button>
                <button class="filter-btn" data-year="2024">2024</button>
                <button class="filter-btn" data-year="2023">2023</button>
            </div>

            <div class="press-grid <?= isset($_SESSION['username']) ? 'admin-view' : '' ?>">
                <?php if (empty($press_items)): ?>
                    <div class="empty-state">
                        <i class="far fa-newspaper"></i>
                        <h3>No press coverage yet</h3>
                        <p>Check back later for updates on our media appearances.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($press_items as $id => $item): ?>
                        <a href="?article=<?= $id ?>" class="press-card-link">
                            <div class="press-card <?= isset($_SESSION['username']) ? 'admin-view' : '' ?>"
                                 data-year="<?= htmlspecialchars($item['year']) ?>">

                                <div class="card-image">
                                    <?php if (!empty($item['cover_img'])): ?>
                                        <img src="<?= htmlspecialchars($item['cover_img']) ?>"
                                             alt="<?= htmlspecialchars($item['blog_title']) ?>">
                                    <?php else: ?>
                                        <div class="card-no-image">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="card-content">
                                    <span class="press-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        <?= htmlspecialchars(explode(' ', $item['upload_date'])[0]) ?>
                                    </span>
                                    <h3 class="press-title"><?= htmlspecialchars($item['blog_title']) ?></h3>
                                    <div class="press-source">
                                        <i class="fas fa-newspaper"></i>
                                        <span><?= htmlspecialchars($item['blog_author']) ?></span>
                                    </div>
                                    <p class="press-summary"
                                       id="summary-<?= $id ?>"
                                       data-full-text="<?= htmlspecialchars($item['summary'], ENT_QUOTES) ?>"><?php
                                        $words = explode(' ', $item['summary']);
                                        echo count($words) > 20
                                            ? htmlspecialchars_decode(implode(' ', array_slice($words, 0, 20))) . '...'
                                            : htmlspecialchars_decode($item['summary']);
                                    ?></p>
                                    <div class="press-actions">
                                        <?php if (isset($_SESSION['username'])): ?>
                                            <a class="press-button update"
                                               href="<?= UPDATE_BLOG_IMAGE_PATH ?>?id=<?= $item['blog_id'] ?>"
                                               onclick="event.stopPropagation();">
                                                <i class="fas fa-sync-alt"></i> Update
                                            </a>
                                        <?php endif; ?>
                                        <button class="press-button read-more"
                                                onclick="toggleReadMore(<?= $id ?>, event)">
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

    <script src="navbar.js"></script>
    <script src="menutoggle.js"></script>
    <script>
        function toggleReadMore(id, e) {
            e.preventDefault();
            e.stopPropagation();
            var el  = document.getElementById('summary-' + id);
            var btn = e.currentTarget;
            var full = el.getAttribute('data-full-text');

            if (el.classList.contains('expanded')) {
                var words = full.split(' ');
                el.innerHTML = words.length > 20 ? words.slice(0, 20).join(' ') + '...' : full;
                el.classList.remove('expanded');
                btn.innerHTML = 'Read More <i class="fas fa-arrow-right"></i>';
            } else {
                el.innerHTML = full;
                el.classList.add('expanded');
                btn.innerHTML = 'Read Less <i class="fas fa-arrow-up"></i>';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            <?php if (!$current_article): ?>
            var filterButtons = document.querySelectorAll('.filter-btn');
            var cardLinks     = document.querySelectorAll('.press-card-link');

            filterButtons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    filterButtons.forEach(function (b) { b.classList.remove('active'); });
                    btn.classList.add('active');
                    var year = btn.dataset.year;
                    cardLinks.forEach(function (link) {
                        var card = link.querySelector('.press-card');
                        link.style.display = (year === 'all' || card.dataset.year === year) ? '' : 'none';
                    });
                });
            });

            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) entry.target.classList.add('show');
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.press-card').forEach(function (card) {
                observer.observe(card);
            });
            <?php endif; ?>
        });
    </script>
</body>

</html>
