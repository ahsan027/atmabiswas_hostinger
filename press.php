<?php
session_start();
include 'config.php';
include 'backend/Database/db.php';

function extractYouTubeId(string $url): string {
    if (empty($url)) return '';
    if (strpos($url, 'youtu.be/') !== false) {
        $path = ltrim(parse_url($url, PHP_URL_PATH) ?? '', '/');
        return preg_replace('/[^a-zA-Z0-9_-]/', '', strtok($path, '?&'));
    }
    if (preg_match('/[?&]v=([a-zA-Z0-9_-]+)/', $url, $m)) return $m[1];
    return '';
}

function getCardMedia(array $item): array {
    if (!empty($item['cover_img'])) {
        return ['type' => 'image', 'url' => $item['cover_img']];
    }
    $ytId = extractYouTubeId($item['source_link'] ?? '');
    if ($ytId) {
        return ['type' => 'youtube', 'id' => $ytId, 'url' => "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg"];
    }
    return ['type' => 'none'];
}

function getCategoryInfo(string $cat): array {
    $map = [
        'media'        => ['label' => 'Media',        'class' => 'pr-badge--media'],
        'announcement' => ['label' => 'Announcement', 'class' => 'pr-badge--announcement'],
        'press'        => ['label' => 'Press Release', 'class' => 'pr-badge--press'],
    ];
    return $map[$cat] ?? ['label' => 'News', 'class' => 'pr-badge--news'];
}

// Fetch all published posts
$indexed = [];
try {
    $db   = new Db();
    $conn = $db->connect();
    $stmt = $conn->prepare("SELECT * FROM blogs ORDER BY upload_date DESC");
    $stmt->execute();
    $indexed = array_values($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    $indexed = [];
}

// Determine view mode
$article_id      = isset($_GET['article']) ? (int)$_GET['article'] : null;
$current_article = ($article_id !== null && isset($indexed[$article_id])) ? $indexed[$article_id] : null;

// Build filter options from live data
$available_years      = [];
$available_categories = [];
foreach ($indexed as $item) {
    if (!empty($item['year'])) $available_years[$item['year']] = true;
    $cat = $item['category'] ?? 'news';
    $available_categories[$cat] = true;
}
krsort($available_years);

// Related articles for article view
$related_articles = [];
if ($current_article) {
    foreach ($indexed as $rid => $ritem) {
        if ($rid !== $article_id) {
            $related_articles[$rid] = $ritem;
            if (count($related_articles) >= 3) break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if ($current_article): ?>
    <title><?= htmlspecialchars($current_article['blog_title']) ?> — ATMABISWAS Press</title>
    <?php else: ?>
    <title>Press &amp; Media — ATMABISWAS</title>
    <?php endif; ?>
    <?php include 'seo.php'; ?>
    <link rel="icon" type="image/png" href="LOGO/NGO_logo_monogram.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="css/navbar.css?v=<?= filemtime(__DIR__.'/css/navbar.css') ?>">
    <link rel="stylesheet" href="css/menutoggle.css?v=<?= filemtime(__DIR__.'/css/menutoggle.css') ?>">
    <link rel="stylesheet" href="css/sidebar.css?v=<?= filemtime(__DIR__.'/css/sidebar.css') ?>">
    <link rel="stylesheet" href="press.css?v=<?= filemtime(__DIR__.'/press.css') ?>">
</head>
<body>

<?php include 'Navbar.php'; ?>

<?php if ($current_article):
    // ═══════════════════════════════════════════
    // ARTICLE VIEW
    // ═══════════════════════════════════════════
    $cat_info  = getCategoryInfo($current_article['category'] ?? 'news');
    $media     = getCardMedia($current_article);
    $yt_id     = ($media['type'] === 'youtube') ? $media['id'] : '';
    $date_fmt  = !empty($current_article['upload_date'])
                    ? date('F j, Y', strtotime($current_article['upload_date'])) : '';
?>
<div class="pr-hero pr-article-hero">
    <a href="press.php" class="pr-back-btn">
        <i class="fas fa-arrow-left"></i> Back to Press
    </a>
    <span class="pr-hero-label"><?= htmlspecialchars($cat_info['label']) ?></span>
    <h1><?= htmlspecialchars($current_article['blog_title']) ?></h1>
    <div class="pr-article-meta">
        <?php if ($date_fmt): ?>
        <span><i class="far fa-calendar-alt"></i> <?= $date_fmt ?></span>
        <?php endif; ?>
        <?php if (!empty($current_article['blog_author'])): ?>
        <span><i class="far fa-user"></i> <?= htmlspecialchars($current_article['blog_author']) ?></span>
        <?php endif; ?>
    </div>
</div>

<div class="pr-article-wrap">

    <?php if ($yt_id): ?>
    <div class="pr-article-media">
        <div class="pr-article-video">
            <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($yt_id) ?>"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
        </div>
    </div>
    <?php elseif (!empty($current_article['cover_img'])): ?>
    <div class="pr-article-media">
        <img src="<?= htmlspecialchars($current_article['cover_img']) ?>"
             alt="<?= htmlspecialchars($current_article['image_title'] ?? $current_article['blog_title']) ?>">
    </div>
    <?php endif; ?>

    <div class="pr-article-body">
        <?= $current_article['blog_content'] ?>
    </div>

    <?php if (!empty($related_articles)): ?>
    <div class="pr-related">
        <div class="pr-related-heading">More Press Updates</div>
        <div class="pr-related-grid">
            <?php foreach ($related_articles as $rid => $rel):
                $rel_media    = getCardMedia($rel);
                $rel_cat_info = getCategoryInfo($rel['category'] ?? 'news');
                $rel_date     = !empty($rel['upload_date']) ? date('M j, Y', strtotime($rel['upload_date'])) : '';
            ?>
            <a href="press.php?article=<?= $rid ?>" class="press-card-link">
                <div class="press-card">
                    <div class="pr-card-media">
                        <?php if ($rel_media['type'] !== 'none'): ?>
                            <img src="<?= htmlspecialchars($rel_media['url']) ?>"
                                 alt="<?= htmlspecialchars($rel['blog_title']) ?>"
                                 loading="lazy">
                            <?php if ($rel_media['type'] === 'youtube'): ?>
                            <div class="yt-play-overlay">
                                <div class="yt-play-btn"><i class="fas fa-play"></i></div>
                            </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="pr-card-media-empty"><i class="far fa-newspaper"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="pr-card-body">
                        <div class="pr-card-header">
                            <span class="pr-badge <?= $rel_cat_info['class'] ?>"><?= $rel_cat_info['label'] ?></span>
                            <span class="pr-card-date"><?= $rel_date ?></span>
                        </div>
                        <div class="pr-card-title"><?= htmlspecialchars($rel['blog_title']) ?></div>
                        <div class="pr-card-footer">
                            <span></span>
                            <span class="pr-read-more">Read <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php else:
    // ═══════════════════════════════════════════
    // LIST VIEW
    // ═══════════════════════════════════════════
?>
<div class="pr-hero">
    <span class="pr-hero-label">ATMABISWAS — Press &amp; Media</span>
    <h1>Press &amp; News Room</h1>
    <p>Updates, announcements, and media coverage from ATMABISWAS Bangladesh</p>
</div>

<div class="pr-main">

    <?php if (!empty($indexed)): ?>
    <div class="pr-filters">
        <?php if (count($available_categories) > 1): ?>
        <div class="pr-filter-group">
            <span class="pr-filter-label">Category</span>
            <div class="pr-filter-pills" id="categoryPills">
                <button class="pr-pill active" data-filter-cat="all">All</button>
                <?php foreach (array_keys($available_categories) as $cat):
                    $ci = getCategoryInfo($cat); ?>
                <button class="pr-pill" data-filter-cat="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($ci['label']) ?></button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($available_years)): ?>
        <div class="pr-filter-group">
            <span class="pr-filter-label">Year</span>
            <div class="pr-filter-pills" id="yearPills">
                <button class="pr-pill active" data-filter-year="all">All</button>
                <?php foreach (array_keys($available_years) as $yr): ?>
                <button class="pr-pill" data-filter-year="<?= (int)$yr ?>"><?= (int)$yr ?></button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (empty($indexed)): ?>
    <div class="pr-empty">
        <i class="far fa-newspaper"></i>
        <p>No press updates yet. Check back soon.</p>
    </div>
    <?php else: ?>
    <div class="pr-grid" id="pressGrid">
        <?php foreach ($indexed as $idx => $item):
            $media    = getCardMedia($item);
            $cat_info = getCategoryInfo($item['category'] ?? 'news');
            $date_fmt = !empty($item['upload_date']) ? date('M j, Y', strtotime($item['upload_date'])) : '';
            $cat_val  = htmlspecialchars($item['category'] ?? 'news');
            $year_val = (int)($item['year'] ?? 0);
            $summary  = !empty($item['summary']) ? mb_substr(strip_tags($item['summary']), 0, 130) : '';
        ?>
        <a href="press.php?article=<?= $idx ?>"
           class="press-card-link"
           data-year="<?= $year_val ?>"
           data-category="<?= $cat_val ?>">
            <div class="press-card">
                <div class="pr-card-media">
                    <?php if ($media['type'] !== 'none'): ?>
                        <img src="<?= htmlspecialchars($media['url']) ?>"
                             alt="<?= htmlspecialchars($item['blog_title']) ?>"
                             loading="lazy">
                        <?php if ($media['type'] === 'youtube'): ?>
                        <div class="yt-play-overlay">
                            <div class="yt-play-btn"><i class="fas fa-play"></i></div>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="pr-card-media-empty"><i class="far fa-newspaper"></i></div>
                    <?php endif; ?>
                </div>
                <div class="pr-card-body">
                    <div class="pr-card-header">
                        <span class="pr-badge <?= $cat_info['class'] ?>"><?= $cat_info['label'] ?></span>
                        <?php if ($date_fmt): ?>
                        <span class="pr-card-date"><?= $date_fmt ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="pr-card-title"><?= htmlspecialchars($item['blog_title']) ?></div>
                    <?php if ($summary): ?>
                    <div class="pr-card-summary"><?= htmlspecialchars($summary) ?>…</div>
                    <?php endif; ?>
                    <div class="pr-card-footer">
                        <?php if (!empty($item['blog_author'])): ?>
                        <span class="pr-card-author"><i class="far fa-user"></i> <?= htmlspecialchars($item['blog_author']) ?></span>
                        <?php endif; ?>
                        <span class="pr-read-more">Read more <i class="fas fa-arrow-right"></i></span>
                    </div>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
        <div class="pr-no-results" id="noResults" style="display:none;">
            <i class="far fa-search"></i>
            <p>No posts match the selected filters.</p>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
(function () {
    var activeCat  = 'all';
    var activeYear = 'all';

    function applyFilters() {
        var links   = document.querySelectorAll('#pressGrid .press-card-link');
        var visible = 0;
        links.forEach(function (link) {
            var cat  = link.dataset.category || 'news';
            var year = link.dataset.year     || '0';
            var show = (activeCat  === 'all' || cat  === activeCat) &&
                       (activeYear === 'all' || year === activeYear);
            link.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        var noRes = document.getElementById('noResults');
        if (noRes) noRes.style.display = (visible === 0) ? 'block' : 'none';
    }

    function bindPills(containerId, prop) {
        var wrap = document.getElementById(containerId);
        if (!wrap) return;
        wrap.addEventListener('click', function (e) {
            var btn = e.target.closest('.pr-pill');
            if (!btn) return;
            wrap.querySelectorAll('.pr-pill').forEach(function (p) { p.classList.remove('active'); });
            btn.classList.add('active');
            if (prop === 'cat')  activeCat  = btn.dataset.filterCat  || 'all';
            if (prop === 'year') activeYear = btn.dataset.filterYear || 'all';
            applyFilters();
        });
    }

    bindPills('categoryPills', 'cat');
    bindPills('yearPills',     'year');
}());
</script>
<?php endif; ?>

<?php include 'footer.php'; ?>
</body>
</html>
