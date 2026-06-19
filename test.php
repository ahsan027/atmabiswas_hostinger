<?php
// Latest News image grid — included by index.php
require_once 'backend/Database/db.php';
try {
    $db   = new Db();
    $conn = $db->connect();

    // Auto-add display_order column if the table doesn't have it yet
    $col = $conn->query(
        "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE()
           AND TABLE_NAME   = 'img_upload'
           AND COLUMN_NAME  = 'display_order'"
    );
    if ((int)$col->fetchColumn() === 0) {
        $conn->exec("ALTER TABLE img_upload ADD COLUMN display_order INT NOT NULL DEFAULT 0");
    }

    $stmt = $conn->prepare(
        "SELECT img_title, img_description, img_path, display_order
         FROM img_upload
         WHERE img_type = 'latest_news'
         ORDER BY display_order ASC, img_path ASC
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
/* ── Latest News grid ──────────────────────────────── */
.ln-section {
    background: #f4f7f6;
    padding: 8px 20px 64px;
}
.ln-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(275px, 1fr));
    gap: 28px;
    max-width: 1200px;
    margin: 0 auto;
}

/* ── Card ───────────────────────────────────────────── */
.ln-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,.07);
    overflow: hidden;
    transition: transform .3s ease, box-shadow .3s ease;
    border: 1px solid #e5e7eb;
    display: flex;
    flex-direction: column;
}
.ln-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(0,0,0,.13);
}

/* ── Image wrap ─────────────────────────────────────── */
.ln-card-img-wrap {
    position: relative;
    overflow: hidden;
    height: 200px;
    flex-shrink: 0;
}
.ln-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .4s ease;
}
.ln-card:hover .ln-card-img { transform: scale(1.06); }

/* ── Accent line ────────────────────────────────────── */
.ln-card-accent {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #0073e6, #1e3a5f);
}

/* ── Body ───────────────────────────────────────────── */
.ln-card-body {
    padding: 16px 18px 18px;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.ln-card-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1e3a5f;
    margin: 0 0 8px;
    line-height: 1.45;
    font-family: "Times New Roman", Times, serif;
}

/* ── Description + Read More ─────────────────────────── */
.ln-desc-wrap {
    overflow: hidden;
    transition: max-height .35s ease;
    max-height: 2.9em; /* ~2 lines at 1.45 line-height */
}
.ln-desc-wrap.expanded {
    max-height: 600px;
}
.ln-card-desc {
    font-size: .85rem;
    color: #6b7280;
    line-height: 1.6;
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}
.ln-read-more {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    margin-top: 8px;
    background: none;
    border: none;
    padding: 0;
    color: #0073e6;
    font-size: .8rem;
    font-weight: 700;
    cursor: pointer;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    transition: color .2s;
    align-self: flex-start;
}
.ln-read-more:hover { color: #005bb5; }
.ln-read-more i { font-size: .7rem; transition: transform .25s; }
.ln-read-more.expanded i { transform: rotate(180deg); }

@media (max-width: 640px) {
    .ln-grid { grid-template-columns: 1fr 1fr; gap: 14px; }
    .ln-card-img-wrap { height: 150px; }
    .ln-section { padding: 8px 12px 48px; }
}
@media (max-width: 420px) {
    .ln-grid { grid-template-columns: 1fr; }
}
</style>

<section class="ln-section">
    <div class="ln-grid">
        <?php foreach ($latest as $i => $img):
            $descId  = 'lndesc-' . $i;
            $btnId   = 'lnbtn-'  . $i;
            $hasDesc = !empty(trim($img['img_description']));
        ?>
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
                <?php if ($hasDesc): ?>
                <div class="ln-desc-wrap" id="<?= $descId ?>">
                    <p class="ln-card-desc"><?= htmlspecialchars($img['img_description']) ?></p>
                </div>
                <button class="ln-read-more" id="<?= $btnId ?>"
                        onclick="lnToggle('<?= $descId ?>','<?= $btnId ?>')">
                    Read More <i class="fas fa-chevron-down"></i>
                </button>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<script>
function lnToggle(descId, btnId) {
    var wrap = document.getElementById(descId);
    var btn  = document.getElementById(btnId);
    var open = wrap.classList.toggle('expanded');
    btn.classList.toggle('expanded', open);
    btn.querySelector('i').style.transform = open ? 'rotate(180deg)' : 'rotate(0deg)';
    // Update text, keep icon
    btn.childNodes[0].textContent = open ? 'Read Less ' : 'Read More ';
}
</script>
