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

