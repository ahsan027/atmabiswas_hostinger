<?php
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
                        <span><?php echo $current_article["upload_date"]; ?></span>
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
            <?php include 'Navbar.php'; ?>
            <header>
                <h1>Press & Media – ATMABISWAS</h1>
                <p>Showcasing our work through national and regional media—covering our impact, initiatives, and stories that inspire social transformation.</p>
            </header>

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
                                    <span class="press-date"><?php echo $item["upload_date"]; ?></span>
                                    <h3 class="press-title"><?php echo $item['blog_title']; ?></h3>
                                    <div class="press-source">
                                        <i class="fas fa-newspaper"></i>
                                        <span><?php echo $item['blog_author']; ?></span>
                                    </div>
                                    <p class="press-summary"><?php echo $item['summary']; ?></p>

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