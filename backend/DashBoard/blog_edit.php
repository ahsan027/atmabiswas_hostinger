<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login/loging.php");
    exit();
}

require_once '../../config.php';

$blog_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$blog_id) {
    header("Location: blog_manager.php");
    exit();
}

$pdo = null;
try {
    include '../Database/db.php';
    $pdo = (new Db())->connect();
} catch (Exception $e) {
    die('Database connection failed.');
}

/* ── Detect available columns ────────────────────────────────────── */
$existing_cols = $pdo->query(
    "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
     WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='blogs'"
)->fetchAll(PDO::FETCH_COLUMN);
$has = array_flip($existing_cols);

/* ── Fetch post ──────────────────────────────────────────────────── */
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE blog_id = ?");
$stmt->execute([$blog_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$post) {
    header("Location: blog_manager.php");
    exit();
}

/* ── Handle form submission ──────────────────────────────────────── */
$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title   = trim($_POST['blog_title']    ?? '');
        $content = $_POST['blog_content']       ?? '';
        $summary = $_POST['summary_content']    ?? '';

        if (empty($title))   throw new Exception('Title is required.');
        if (empty($content)) throw new Exception('Content is required.');
        if (empty($summary)) throw new Exception('Summary is required.');

        /* Build UPDATE dynamically — only set columns that exist */
        $sets   = ['blog_title=?', 'blog_content=?', 'summary=?'];
        $values = [$title, $content, $summary];

        if (isset($has['status'])) {
            $sets[]   = 'status=?';
            $values[] = $_POST['status'] ?? 'published';
        }
        if (isset($has['category'])) {
            $sets[]   = 'category=?';
            $values[] = $_POST['category'] ?? 'news';
        }
        if (isset($has['source_link'])) {
            $sets[]   = 'source_link=?';
            $values[] = trim($_POST['source_link'] ?? '');
        }
        if (isset($has['tags'])) {
            $sets[]   = 'tags=?';
            $values[] = trim($_POST['tags'] ?? '');
        }
        if (isset($has['featured'])) {
            $sets[]   = 'featured=?';
            $values[] = isset($_POST['featured']) ? 1 : 0;
        }
        if (isset($has['seo_title'])) {
            $sets[]   = 'seo_title=?';
            $values[] = trim($_POST['seo_title'] ?? '');
        }
        if (isset($has['seo_description'])) {
            $sets[]   = 'seo_description=?';
            $values[] = trim($_POST['seo_description'] ?? '');
        }
        if (isset($has['reading_time'])) {
            $sets[]   = 'reading_time=?';
            $values[] = max(1, (int)ceil(str_word_count(strip_tags($content)) / 200));
        }
        /* last_updated auto-updates via ON UPDATE CURRENT_TIMESTAMP — no need to set it */

        $values[] = $blog_id;
        $sql = "UPDATE blogs SET " . implode(', ', $sets) . " WHERE blog_id=?";
        $pdo->prepare($sql)->execute($values);

        $success = 'Article updated successfully.';

        /* Refresh */
        $stmt = $pdo->prepare("SELECT * FROM blogs WHERE blog_id = ?");
        $stmt->execute([$blog_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$cat_options = [
    'news'         => 'News',
    'media'        => 'Media',
    'announcement' => 'Announcement',
    'press'        => 'Press Release',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Article — ATMABISWAS Admin</title>
<link rel="icon" type="image/png" href="../images/logo/logo.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root { --pri:#0073e6; --dark:#1e3a5f; }
body { background:#f5f7fa; font-family:system-ui,-apple-system,'Segoe UI',sans-serif; }
.am-header { background:linear-gradient(135deg,var(--dark) 0%,var(--pri) 100%);
             color:#fff; padding:1.25rem 0; margin-bottom:1.75rem; }
.am-header h1 { font-size:1.35rem; font-weight:800; margin:0; }
.panel { background:#fff; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,.07); padding:1.5rem; margin-bottom:1.25rem; }
.panel-title { font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:#94a3b8; margin-bottom:1rem; }
.form-label { font-size:.82rem; font-weight:700; color:#374151; margin-bottom:.35rem; }
.editor-area {
    min-height:220px; border:1.5px solid #e2e8f0; border-radius:8px;
    padding:1rem; background:#fff; font-size:.92rem; line-height:1.7;
}
.editor-area:focus { outline:none; border-color:var(--pri); box-shadow:0 0 0 3px rgba(0,115,230,.1); }
.toolbar { background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:8px; padding:.6rem .75rem; margin-bottom:.5rem; }
.toolbar .btn { padding:.2rem .45rem; font-size:.8rem; }
.char-counter { font-size:.72rem; color:#94a3b8; text-align:right; margin-top:.25rem; }
</style>
</head>
<body>

<div class="am-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1><i class="fas fa-edit me-2"></i>Edit Article</h1>
            <div class="d-flex gap-2">
                <a href="../../press.php?id=<?= $blog_id ?>" target="_blank" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-eye"></i> Preview
                </a>
                <a href="blog_manager.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <?php if ($success): ?>
    <div class="alert alert-success alert-dismissible rounded-3 fade show">
        <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible rounded-3 fade show">
        <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <form method="POST" id="editForm">
    <div class="row g-3">

        <!-- Left column: content -->
        <div class="col-lg-8">

            <!-- Title -->
            <div class="panel">
                <div class="panel-title">Article Title</div>
                <input type="text" class="form-control" name="blog_title"
                       value="<?= htmlspecialchars($post['blog_title']) ?>"
                       placeholder="Article title…" required maxlength="255">
            </div>

            <!-- Summary -->
            <div class="panel">
                <div class="panel-title">Summary <span class="text-danger">*</span></div>
                <div class="toolbar">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fmt('bold')" title="Bold"><i class="fas fa-bold"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fmt('italic')" title="Italic"><i class="fas fa-italic"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fmt('underline')" title="Underline"><i class="fas fa-underline"></i></button>
                </div>
                <div id="summaryEditor" contenteditable="true" class="editor-area"
                     style="min-height:100px"
                     oninput="syncHidden('summary')"><?= $post['summary'] ?? '' ?></div>
                <input type="hidden" name="summary_content" id="summaryHidden">
            </div>

            <!-- Content -->
            <div class="panel">
                <div class="panel-title">Article Content <span class="text-danger">*</span></div>
                <div class="toolbar">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fmt('bold')"><i class="fas fa-bold"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fmt('italic')"><i class="fas fa-italic"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fmt('underline')"><i class="fas fa-underline"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fmt('insertUnorderedList')"><i class="fas fa-list-ul"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fmt('insertOrderedList')"><i class="fas fa-list-ol"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertLink()"><i class="fas fa-link"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fmt('justifyLeft')"><i class="fas fa-align-left"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fmt('justifyCenter')"><i class="fas fa-align-center"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fmt('justifyRight')"><i class="fas fa-align-right"></i></button>
                </div>
                <div id="contentEditor" contenteditable="true" class="editor-area"
                     oninput="syncHidden('content')"><?= $post['blog_content'] ?? '' ?></div>
                <input type="hidden" name="blog_content" id="contentHidden">
            </div>

        </div>

        <!-- Right column: meta -->
        <div class="col-lg-4">

            <!-- Publish -->
            <div class="panel">
                <div class="panel-title">Publish Settings</div>

                <?php if (isset($has['status'])): ?>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select form-select-sm" name="status">
                        <option value="published" <?= ($post['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="draft"     <?= ($post['status'] ?? '') === 'draft'     ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>
                <?php endif; ?>

                <?php if (isset($has['category'])): ?>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select class="form-select form-select-sm" name="category">
                        <?php foreach ($cat_options as $k => $v): ?>
                        <option value="<?= $k ?>" <?= ($post['category'] ?? 'news') === $k ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <?php if (isset($has['featured'])): ?>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch"
                           id="featuredCheck" name="featured" value="1"
                           <?= !empty($post['featured']) ? 'checked' : '' ?>>
                    <label class="form-check-label form-label mb-0" for="featuredCheck">
                        Featured Article
                    </label>
                </div>
                <?php endif; ?>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="blog_manager.php" class="btn btn-outline-secondary btn-sm">Cancel</a>
                </div>
            </div>

            <!-- Post Info -->
            <div class="panel">
                <div class="panel-title">Article Info</div>
                <div class="small text-muted">
                    <div class="mb-1"><span class="fw-bold">Author:</span> <?= htmlspecialchars($post['blog_author'] ?? '—') ?></div>
                    <div class="mb-1"><span class="fw-bold">Created:</span>
                        <?= !empty($post['upload_date']) ? date('M j, Y g:i A', strtotime($post['upload_date'])) : '—' ?>
                    </div>
                    <?php if (!empty($post['last_updated'])): ?>
                    <div class="mb-1"><span class="fw-bold">Updated:</span>
                        <?= date('M j, Y g:i A', strtotime($post['last_updated'])) ?>
                    </div>
                    <?php endif; ?>
                    <?php if (isset($has['views'])): ?>
                    <div class="mb-1"><span class="fw-bold">Views:</span> <?= number_format((int)($post['views'] ?? 0)) ?></div>
                    <?php endif; ?>
                    <div><span class="fw-bold">ID:</span> #<?= $blog_id ?></div>
                </div>
            </div>

            <!-- Source / Media link -->
            <?php if (isset($has['source_link'])): ?>
            <div class="panel">
                <div class="panel-title">Source / Media Link</div>
                <input type="url" class="form-control form-control-sm" name="source_link"
                       placeholder="https://youtube.com/watch?v=…"
                       value="<?= htmlspecialchars($post['source_link'] ?? '') ?>">
                <div class="char-counter">YouTube URL or external source</div>
            </div>
            <?php endif; ?>

            <!-- Tags -->
            <?php if (isset($has['tags'])): ?>
            <div class="panel">
                <div class="panel-title">Tags</div>
                <input type="text" class="form-control form-control-sm" name="tags"
                       placeholder="microfinance, rural, health"
                       value="<?= htmlspecialchars($post['tags'] ?? '') ?>">
                <div class="char-counter">Comma-separated keywords</div>
            </div>
            <?php endif; ?>

            <!-- SEO -->
            <?php if (isset($has['seo_title'])): ?>
            <div class="panel">
                <div class="panel-title">SEO</div>
                <div class="mb-2">
                    <label class="form-label">SEO Title</label>
                    <input type="text" class="form-control form-control-sm" name="seo_title"
                           maxlength="60" id="seoTitle"
                           value="<?= htmlspecialchars($post['seo_title'] ?? '') ?>"
                           oninput="document.getElementById('seoTitleCnt').textContent=this.value.length+'/60'">
                    <div class="char-counter"><span id="seoTitleCnt"><?= strlen($post['seo_title'] ?? '') ?>/60</span></div>
                </div>
                <div>
                    <label class="form-label">Meta Description</label>
                    <textarea class="form-control form-control-sm" name="seo_description"
                              maxlength="160" rows="3" id="seoDesc"
                              oninput="document.getElementById('seoDescCnt').textContent=this.value.length+'/160'"><?= htmlspecialchars($post['seo_description'] ?? '') ?></textarea>
                    <div class="char-counter"><span id="seoDescCnt"><?= strlen($post['seo_description'] ?? '') ?>/160</span></div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let lastFocus = document.getElementById('contentEditor');

['summaryEditor','contentEditor'].forEach(id => {
    document.getElementById(id).addEventListener('focus', () => { lastFocus = document.getElementById(id); });
});

function fmt(cmd) {
    lastFocus.focus();
    document.execCommand(cmd, false, null);
    syncHidden(lastFocus.id === 'summaryEditor' ? 'summary' : 'content');
}

function insertLink() {
    lastFocus.focus();
    const url = prompt('Enter URL:');
    if (url) document.execCommand('createLink', false, url);
}

function syncHidden(type) {
    if (type === 'summary') {
        document.getElementById('summaryHidden').value = document.getElementById('summaryEditor').innerHTML;
    } else {
        document.getElementById('contentHidden').value = document.getElementById('contentEditor').innerHTML;
    }
}

document.getElementById('editForm').addEventListener('submit', () => {
    syncHidden('summary');
    syncHidden('content');
});

// Init hidden inputs on load
window.addEventListener('DOMContentLoaded', () => {
    syncHidden('summary');
    syncHidden('content');
});
</script>
</body>
</html>
