<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login/loging.php");
    exit();
}

require_once '../../config.php';

/* ── DB connection ─────────────────────────────────────────────── */
try {
    include '../Database/db.php';
    $db  = new Db();
    $pdo = $db->connect();
} catch (Exception $e) {
    $db_error = 'Could not connect to the database. Please check your connection settings.';
    $pdo = null;
}

/* ── Detect which columns actually exist ────────────────────────── */
$has_views    = false;
$has_status   = false;
$has_featured = false;
$has_category = false;

if ($pdo) {
    try {
        $col_stmt = $pdo->query(
            "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'blogs'"
        );
        $existing_cols = $col_stmt->fetchAll(PDO::FETCH_COLUMN);
        $has_views    = in_array('views',    $existing_cols);
        $has_status   = in_array('status',   $existing_cols);
        $has_featured = in_array('featured', $existing_cols);
        $has_category = in_array('category', $existing_cols);
    } catch (PDOException $e) {
        // proceed with all flags false — queries will use safe fallbacks
    }
}

/* ── Handle quick actions ────────────────────────────────────────── */
$action  = $_GET['action'] ?? '';
$blog_id = (int)($_GET['id'] ?? 0);
$message = '';
$msg_type = 'success';

if ($action && $blog_id && $pdo) {
    try {
        switch ($action) {
            case 'delete':
                $pdo->prepare("DELETE FROM blogs WHERE blog_id = ?")->execute([$blog_id]);
                $message = 'Article deleted successfully.';
                break;
            case 'publish':
                if ($has_status) {
                    $pdo->prepare("UPDATE blogs SET status = 'published' WHERE blog_id = ?")->execute([$blog_id]);
                    $message = 'Article published.';
                } else {
                    $message = 'Status column not available — run the database migration first.';
                    $msg_type = 'warning';
                }
                break;
            case 'draft':
                if ($has_status) {
                    $pdo->prepare("UPDATE blogs SET status = 'draft' WHERE blog_id = ?")->execute([$blog_id]);
                    $message = 'Article moved to drafts.';
                } else {
                    $message = 'Status column not available — run the database migration first.';
                    $msg_type = 'warning';
                }
                break;
            case 'feature':
                if ($has_featured) {
                    $pdo->prepare("UPDATE blogs SET featured = 1 WHERE blog_id = ?")->execute([$blog_id]);
                    $pdo->prepare("UPDATE blogs SET featured = 0 WHERE blog_id != ?")->execute([$blog_id]);
                    $message = 'Article set as featured.';
                } else {
                    $message = 'Featured column not available — run the database migration first.';
                    $msg_type = 'warning';
                }
                break;
        }
    } catch (PDOException $e) {
        $message  = 'Action failed: ' . htmlspecialchars($e->getMessage());
        $msg_type = 'danger';
    }
}

/* ── Pagination + filters ────────────────────────────────────────── */
$page          = max(1, (int)($_GET['page'] ?? 1));
$limit         = 10;
$offset        = ($page - 1) * $limit;
$search        = trim($_GET['search'] ?? '');
$status_filter = $has_status ? ($_GET['status'] ?? '') : '';

$where_conditions = [];
$params           = [];

if ($search !== '') {
    $where_conditions[] = "(blog_title LIKE ? OR blog_content LIKE ?)";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

if ($status_filter !== '' && $has_status) {
    if ($status_filter === 'published') {
        $where_conditions[] = "(status = 'published' OR status IS NULL)";
    } else {
        $where_conditions[] = "status = ?";
        $params[] = $status_filter;
    }
}

$where_clause = $where_conditions ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

/* ── Build safe SELECT (only reference columns that exist) ─────── */
$select_views    = $has_views    ? "COALESCE(views, 0)"             : "0";
$select_status   = $has_status   ? "COALESCE(status, 'published')"  : "'published'";
$select_featured = $has_featured ? ", featured"                     : ", 0 AS featured";
$select_category = $has_category ? ", category"                     : ", 'news' AS category";

$total_posts  = 0;
$total_pages  = 0;
$posts        = [];
$stats        = ['published' => 0, 'drafts' => 0, 'views' => 0, 'total' => 0];

if ($pdo) {
    try {
        /* Count */
        $count_stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM blogs {$where_clause}");
        $count_stmt->execute($params);
        $total_posts = (int)($count_stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
        $total_pages = (int)ceil($total_posts / $limit);

        /* Posts */
        $sql = "
            SELECT
                blog_id, blog_title, blog_author, upload_date, cover_img,
                {$select_status}   AS status,
                {$select_views}    AS views
                {$select_featured}
                {$select_category},
                SUBSTRING(blog_content, 1, 200) AS excerpt
            FROM blogs
            {$where_clause}
            ORDER BY upload_date DESC
            LIMIT {$limit} OFFSET {$offset}
        ";
        $stmt  = $pdo->prepare($sql);
        $stmt->execute($params);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /* Stats */
        $stats['total'] = $total_posts;
        if ($has_status) {
            $s = $pdo->query("SELECT
                SUM(status = 'published' OR status IS NULL) AS pub,
                SUM(status = 'draft')                       AS dft
                FROM blogs")->fetch(PDO::FETCH_ASSOC);
            $stats['published'] = (int)($s['pub'] ?? 0);
            $stats['drafts']    = (int)($s['dft'] ?? 0);
        } else {
            $stats['published'] = $total_posts;
        }
        if ($has_views) {
            $v = $pdo->query("SELECT COALESCE(SUM(views),0) AS total FROM blogs")->fetch(PDO::FETCH_ASSOC);
            $stats['views'] = (int)($v['total'] ?? 0);
        }
    } catch (PDOException $e) {
        $db_error = 'Query error: ' . $e->getMessage();
        $posts    = [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Manager — ATMABISWAS Admin</title>
    <link rel="icon" type="image/png" href="../images/logo/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0073e6;
            --dark:    #1e3a5f;
        }
        body { background: #f5f7fa; font-family: system-ui, -apple-system, 'Segoe UI', sans-serif; }

        .page-header {
            background: linear-gradient(135deg, var(--dark) 0%, var(--primary) 100%);
            color: #fff;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .page-header h1 { font-size: 1.7rem; font-weight: 800; margin: 0 0 .25rem; }
        .page-header p  { opacity: .8; margin: 0; font-size: .92rem; }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,.07);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        .stat-icon.blue   { background: #dbeafe; color: #1d4ed8; }
        .stat-icon.green  { background: #dcfce7; color: #166534; }
        .stat-icon.yellow { background: #fef9c3; color: #92400e; }
        .stat-icon.purple { background: #ede9fe; color: #5b21b6; }
        .stat-num   { font-size: 1.6rem; font-weight: 800; color: var(--dark); line-height: 1; }
        .stat-label { font-size: .78rem; color: #6b7280; margin-top: .15rem; }

        .filter-bar {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,.07);
            padding: 1.1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .post-row {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
            padding: 1rem 1.25rem;
            margin-bottom: .75rem;
            display: grid;
            grid-template-columns: 70px 1fr auto auto;
            align-items: center;
            gap: 1rem;
        }
        .post-thumb {
            width: 70px; height: 52px;
            object-fit: cover;
            border-radius: 7px;
            display: block;
        }
        .post-thumb-empty {
            width: 70px; height: 52px;
            background: linear-gradient(135deg, var(--dark), var(--primary));
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.4); font-size: 1.1rem;
        }
        .post-title { font-weight: 700; color: var(--dark); font-size: .97rem; line-height: 1.35; }
        .post-meta  { font-size: .78rem; color: #9ca3af; margin-top: .2rem; }
        .post-excerpt { font-size: .82rem; color: #6b7280; margin-top: .3rem;
            display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }

        .status-pill {
            display: inline-block; font-size: .7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .07em;
            padding: .22rem .7rem; border-radius: 20px; white-space: nowrap;
        }
        .pill-published { background: #dcfce7; color: #166534; }
        .pill-draft     { background: #fef9c3; color: #92400e; }
        .pill-archived  { background: #f3f4f6; color: #374151; }

        .action-btns { display: flex; gap: .35rem; flex-wrap: wrap; justify-content: flex-end; }
        .action-btn {
            border: 1.5px solid #d1d5db; background: #fff; color: #374151;
            font-size: .78rem; font-weight: 600; padding: .35rem .7rem;
            border-radius: 6px; text-decoration: none; cursor: pointer;
            transition: all .15s; white-space: nowrap; font-family: inherit;
        }
        .action-btn:hover { border-color: var(--primary); color: var(--primary); }
        .action-btn.del:hover { border-color: #dc2626; color: #dc2626; }
        .action-btn.pub  { border-color: #16a34a; color: #16a34a; }
        .action-btn.feat { border-color: #d97706; color: #d97706; }

        .empty-state { text-align: center; padding: 4rem 2rem; background: #fff;
            border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.07); color: #9ca3af; }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; display: block; color: #d1d5db; }

        .migration-notice {
            background: #fef3c7; border: 1.5px solid #fcd34d;
            border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.5rem;
            font-size: .88rem; color: #92400e;
        }
        .migration-notice code { background: rgba(0,0,0,.08); padding: .1rem .35rem; border-radius: 4px; }
    </style>
</head>
<body>

<div class="page-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1><i class="fas fa-newspaper me-2"></i> Article Manager</h1>
                <p>ATMABISWAS News &amp; Media Center — manage all articles</p>
            </div>
            <div class="d-flex gap-2">
                <a href="blog_enhanced.php" class="btn btn-light fw-bold">
                    <i class="fas fa-plus"></i> Add New Article
                </a>
                <a href="<?= DASHBOARD_PATH ?>" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">

    <?php if (!empty($db_error)): ?>
    <div class="alert alert-danger rounded-3">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Database Error:</strong> <?= htmlspecialchars($db_error) ?>
    </div>
    <?php endif; ?>

    <?php if ($message): ?>
    <div class="alert alert-<?= $msg_type ?> alert-dismissible rounded-3 fade show">
        <i class="fas fa-<?= $msg_type === 'success' ? 'check-circle' : ($msg_type === 'warning' ? 'exclamation-triangle' : 'times-circle') ?> me-2"></i>
        <?= htmlspecialchars($message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if (!$has_views || !$has_status || !$has_featured): ?>
    <div class="migration-notice">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Database upgrade pending.</strong>
        Some columns are missing (<code><?= implode('</code>, <code>', array_filter([
            !$has_views    ? 'views'    : null,
            !$has_status   ? 'status'   : null,
            !$has_featured ? 'featured' : null,
        ])) ?></code>).
        Run <code>database/migrations/upgrade_blogs_table.sql</code> in phpMyAdmin to unlock all features.
        The manager works fine without them.
    </div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-newspaper"></i></div>
                <div>
                    <div class="stat-num"><?= number_format($stats['total']) ?></div>
                    <div class="stat-label">Total Articles</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-num"><?= number_format($stats['published']) ?></div>
                    <div class="stat-label">Published</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon yellow"><i class="fas fa-pencil-alt"></i></div>
                <div>
                    <div class="stat-num"><?= number_format($stats['drafts']) ?></div>
                    <div class="stat-label">Drafts</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-eye"></i></div>
                <div>
                    <div class="stat-num"><?= $has_views ? number_format($stats['views']) : '—' ?></div>
                    <div class="stat-label">Total Views</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search + filter -->
    <div class="filter-bar">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-muted">Search</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control" name="search"
                           placeholder="Search articles by title…"
                           value="<?= htmlspecialchars($search) ?>">
                </div>
            </div>
            <?php if ($has_status): ?>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Status</label>
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="published" <?= $status_filter === 'published' ? 'selected' : '' ?>>Published</option>
                    <option value="draft"     <?= $status_filter === 'draft'     ? 'selected' : '' ?>>Drafts</option>
                </select>
            </div>
            <?php endif; ?>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <?php if ($search || $status_filter): ?>
                    <a href="blog_manager.php" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <!-- Articles list -->
    <?php if (empty($posts)): ?>
    <div class="empty-state">
        <i class="far fa-newspaper"></i>
        <?php if ($search || $status_filter): ?>
        <h5>No articles match your search</h5>
        <p><a href="blog_manager.php">Clear filters</a> to see all articles.</p>
        <?php else: ?>
        <h5>No articles yet</h5>
        <a href="blog_enhanced.php" class="btn btn-primary mt-2">
            <i class="fas fa-plus"></i> Write the first article
        </a>
        <?php endif; ?>
    </div>
    <?php else: ?>

    <?php if ($search || $status_filter): ?>
    <p class="text-muted small mb-2">
        Showing <strong><?= $total_posts ?></strong> result<?= $total_posts !== 1 ? 's' : '' ?>
        <?= $search ? ' for "<strong>' . htmlspecialchars($search) . '</strong>"' : '' ?>
    </p>
    <?php endif; ?>

    <?php foreach ($posts as $post):
        $status  = $post['status'] ?? 'published';
        $views   = number_format((int)($post['views'] ?? 0));
        $date    = !empty($post['upload_date']) ? date('M j, Y', strtotime($post['upload_date'])) : '—';
        $excerpt = htmlspecialchars(strip_tags($post['excerpt'] ?? ''));
        $pill    = match ($status) {
            'draft'    => 'pill-draft',
            'archived' => 'pill-archived',
            default    => 'pill-published',
        };
    ?>
    <div class="post-row">
        <!-- Thumbnail -->
        <div>
            <?php if (!empty($post['cover_img'])): ?>
            <img class="post-thumb"
                 src="<?= htmlspecialchars($post['cover_img']) ?>"
                 alt="Cover">
            <?php else: ?>
            <div class="post-thumb-empty"><i class="far fa-image"></i></div>
            <?php endif; ?>
        </div>

        <!-- Info -->
        <div style="min-width:0;">
            <div class="post-title">
                <a href="../../press.php?id=<?= $post['blog_id'] ?>"
                   target="_blank"
                   style="text-decoration:none;color:inherit;">
                    <?= htmlspecialchars($post['blog_title']) ?>
                </a>
            </div>
            <div class="post-meta">
                <i class="far fa-calendar-alt"></i> <?= $date ?>
                &nbsp;·&nbsp;
                <i class="far fa-user"></i> <?= htmlspecialchars($post['blog_author'] ?? '—') ?>
                <?php if ($has_views): ?>
                &nbsp;·&nbsp;
                <i class="far fa-eye"></i> <?= $views ?>
                <?php endif; ?>
                <?php if ($has_featured && !empty($post['featured'])): ?>
                &nbsp;·&nbsp;
                <i class="fas fa-star text-warning"></i> Featured
                <?php endif; ?>
            </div>
            <?php if ($excerpt): ?>
            <div class="post-excerpt"><?= $excerpt ?></div>
            <?php endif; ?>
        </div>

        <!-- Status -->
        <div>
            <span class="status-pill <?= $pill ?>"><?= ucfirst($status) ?></span>
        </div>

        <!-- Actions -->
        <div class="action-btns">
            <a href="../../press.php?id=<?= $post['blog_id'] ?>"
               target="_blank" class="action-btn" title="View live">
                <i class="fas fa-external-link-alt"></i>
            </a>
            <a href="blog_edit.php?id=<?= $post['blog_id'] ?>"
               class="action-btn" title="Edit">
                <i class="fas fa-edit"></i> Edit
            </a>
            <?php if ($has_featured && empty($post['featured'])): ?>
            <a href="?action=feature&id=<?= $post['blog_id'] ?><?= $search ? '&search='.urlencode($search) : '' ?>"
               class="action-btn feat" title="Set as featured"
               onclick="return confirm('Set this as the featured article?')">
                <i class="fas fa-star"></i>
            </a>
            <?php endif; ?>
            <?php if ($has_status): ?>
                <?php if ($status === 'draft'): ?>
                <a href="?action=publish&id=<?= $post['blog_id'] ?><?= $search ? '&search='.urlencode($search) : '' ?>"
                   class="action-btn pub"
                   onclick="return confirm('Publish this article?')">
                    <i class="fas fa-paper-plane"></i> Publish
                </a>
                <?php else: ?>
                <a href="?action=draft&id=<?= $post['blog_id'] ?><?= $search ? '&search='.urlencode($search) : '' ?>"
                   class="action-btn"
                   onclick="return confirm('Move to drafts?')">
                    <i class="fas fa-archive"></i>
                </a>
                <?php endif; ?>
            <?php endif; ?>
            <a href="?action=delete&id=<?= $post['blog_id'] ?><?= $search ? '&search='.urlencode($search) : '' ?>"
               class="action-btn del"
               onclick="return confirm('Permanently delete this article? This cannot be undone.')">
                <i class="fas fa-trash"></i>
            </a>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <nav class="d-flex justify-content-center gap-2 flex-wrap mt-4">
        <?php if ($page > 1): ?>
        <a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>"
           class="btn btn-outline-secondary btn-sm">← Prev</a>
        <?php endif; ?>
        <?php for ($i = max(1,$page-2); $i <= min($total_pages,$page+2); $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>"
           class="btn btn-sm <?= $i===$page ? 'btn-primary' : 'btn-outline-secondary' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($page < $total_pages): ?>
        <a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status_filter) ?>"
           class="btn btn-outline-secondary btn-sm">Next →</a>
        <?php endif; ?>
    </nav>
    <?php endif; ?>

    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
