<?php
// Latest News image grid — included by index.php
require_once 'backend/Database/db.php';
try {
    $db   = new Db();
    $conn = $db->connect();
    $stmt = $conn->prepare(
        "SELECT img_title, img_description, img_path
         FROM img_upload
         WHERE img_type = 'latest_news'
         ORDER BY img_path DESC
         LIMIT 12"
    );
    $stmt->execute();
    $latest = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $latest = [];
}

if (empty($latest)) return;
?>
<style>
.ln-section {
    background: #f4f7f6;
    padding: 0 20px 64px;
}
.ln-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
    gap: 26px;
    max-width: 1200px;
    margin: 0 auto;
}
.ln-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,.08);
    overflow: hidden;
    transition: transform .3s ease, box-shadow .3s ease;
    border: 1px solid #e5e7eb;
}
.ln-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 36px rgba(0,0,0,.13);
}
.ln-card-img-wrap {
    position: relative;
    overflow: hidden;
    height: 200px;
}
.ln-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .4s ease;
}
.ln-card:hover .ln-card-img {
    transform: scale(1.05);
}
.ln-card-accent {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #0073e6, #1e3a5f);
}
.ln-card-body {
    padding: 16px 18px 20px;
}
.ln-card-title {
    font-size: .98rem;
    font-weight: 700;
    color: #1e3a5f;
    margin: 0 0 8px;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-family: "Times New Roman", Times, serif;
}
.ln-card-desc {
    font-size: .85rem;
    color: #6b7280;
    line-height: 1.6;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}
@media (max-width: 640px) {
    .ln-grid { grid-template-columns: 1fr 1fr; gap: 14px; }
    .ln-card-img-wrap { height: 150px; }
}
@media (max-width: 420px) {
    .ln-grid { grid-template-columns: 1fr; }
}
</style>

<section class="ln-section">
    <div class="ln-grid">
        <?php foreach ($latest as $img): ?>
        <div class="ln-card">
            <div class="ln-card-img-wrap">
                <img class="ln-card-img"
                     src="<?= htmlspecialchars($img['img_path']) ?>"
                     alt="<?= htmlspecialchars($img['img_title']) ?>"
                     loading="lazy">
                <div class="ln-card-accent"></div>
            </div>
            <div class="ln-card-body">
                <h3 class="ln-card-title"><?= htmlspecialchars($img['img_title']) ?></h3>
                <?php if (!empty(trim($img['img_description']))): ?>
                <p class="ln-card-desc"><?= htmlspecialchars($img['img_description']) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
