<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login/loging.php");
    exit();
}

// Include config for paths
require_once '../../config.php';

// Use centralized database connection
include '../Database/db.php';
$db = new Db();
$pdo = $db->connect();

// Handle actions
$action = $_GET['action'] ?? '';
$blog_id = $_GET['id'] ?? '';

if ($action && $blog_id) {
    switch ($action) {
        case 'delete':
            $stmt = $pdo->prepare("DELETE FROM blogs WHERE blog_id = ?");
            $stmt->execute([$blog_id]);
            $message = "Blog post deleted successfully!";
            break;

        case 'publish':
            $stmt = $pdo->prepare("UPDATE blogs SET status = 'published' WHERE blog_id = ?");
            $stmt->execute([$blog_id]);
            $message = "Blog post published successfully!";
            break;

        case 'draft':
            $stmt = $pdo->prepare("UPDATE blogs SET status = 'draft' WHERE blog_id = ?");
            $stmt->execute([$blog_id]);
            $message = "Blog post moved to drafts!";
            break;
    }
}

// Get all posts with pagination
$page = max(1, $_GET['page'] ?? 1);
$limit = 10;
$offset = ($page - 1) * $limit;

$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';

// Build query
$where_conditions = [];
$params = [];

if ($search) {
    $where_conditions[] = "(blog_title LIKE ? OR blog_content LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($status_filter) {
    if ($status_filter === 'published') {
        $where_conditions[] = "(status = 'published' OR status IS NULL)";
    } else {
        $where_conditions[] = "status = ?";
        $params[] = $status_filter;
    }
}

$where_clause = $where_conditions ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get total count
$count_sql = "SELECT COUNT(*) as total FROM blogs $where_clause";
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute($params);
$total_posts = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_posts / $limit);

// Get posts
$sql = "
    SELECT 
        blog_id, 
        blog_title, 
        blog_author, 
        upload_date, 
        COALESCE(status, 'published') as status,
        COALESCE(views, 0) as views,
        cover_img,
        SUBSTRING(blog_content, 1, 200) as excerpt
    FROM blogs 
    $where_clause
    ORDER BY upload_date DESC 
    LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Manager - ATMABISWAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --success: #2ecc71;
            --danger: #e74c3c;
            --warning: #f39c12;
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .post-card {
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .post-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
        }

        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            margin: 0.1rem;
        }

        .search-filters {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .stats-row {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary);
        }

        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        .pagination {
            justify-content: center;
        }

        .no-posts {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .excerpt {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.4;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="fas fa-newspaper"></i> Blog Manager</h1>
                    <p class="mb-0">Manage all your blog posts from one place</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="blog_enhanced.php" class="btn btn-light btn-lg me-2">
                        <i class="fas fa-plus"></i> New Post
                    </a>
                    <a href="<?php echo DASHBOARD_PATH; ?>" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (isset($message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="stats-row">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $total_posts; ?></div>
                        <div class="stat-label">Total Posts</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number" id="publishedCount">-</div>
                        <div class="stat-label">Published</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number" id="draftCount">-</div>
                        <div class="stat-label">Drafts</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number" id="totalViews">-</div>
                        <div class="stat-label">Total Views</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="search-filters">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" name="search"
                            placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="published" <?php echo $status_filter === 'published' ? 'selected' : ''; ?>>Published</option>
                        <option value="draft" <?php echo $status_filter === 'draft' ? 'selected' : ''; ?>>Draft</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Posts List -->
        <?php if (empty($posts)): ?>
            <div class="card">
                <div class="card-body no-posts">
                    <i class="fas fa-newspaper fa-3x mb-3 text-muted"></i>
                    <h4>No posts found</h4>
                    <p>Start by creating your first blog post!</p>
                    <a href="blog_enhanced.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create New Post
                    </a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card post-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-1">
                                <?php if ($post['cover_img']): ?>
                                    <img src="<?php echo htmlspecialchars($post['cover_img']); ?>"
                                        alt="Post thumbnail" class="post-thumbnail">
                                <?php else: ?>
                                    <div class="post-thumbnail bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <h5 class="card-title mb-1">
                                    <a href="blog_content.php?id=<?php echo $post['blog_id']; ?>"
                                        class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($post['blog_title']); ?>
                                    </a>
                                </h5>
                                <div class="excerpt mb-2">
                                    <?php echo htmlspecialchars(strip_tags($post['excerpt'])); ?>...
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($post['blog_author']); ?> |
                                    <i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($post['upload_date'])); ?> |
                                    <i class="fas fa-eye"></i> <?php echo number_format($post['views']); ?> views
                                </small>
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="badge status-badge bg-<?php echo $post['status'] === 'published' ? 'success' : 'warning'; ?>">
                                    <?php echo ucfirst($post['status']); ?>
                                </span>
                            </div>
                            <div class="col-md-3 text-end">
                                <div class="btn-group" role="group">
                                    <a href="blog_content.php?id=<?php echo $post['blog_id']; ?>"
                                        class="btn btn-outline-primary btn-action" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="blog_edit.php?id=<?php echo $post['blog_id']; ?>"
                                        class="btn btn-outline-secondary btn-action" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($post['status'] === 'draft'): ?>
                                        <a href="?action=publish&id=<?php echo $post['blog_id']; ?>"
                                            class="btn btn-outline-success btn-action" title="Publish"
                                            onclick="return confirm('Publish this post?')">
                                            <i class="fas fa-paper-plane"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="?action=draft&id=<?php echo $post['blog_id']; ?>"
                                            class="btn btn-outline-warning btn-action" title="Move to Draft"
                                            onclick="return confirm('Move this post to drafts?')">
                                            <i class="fas fa-archive"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="?action=delete&id=<?php echo $post['blog_id']; ?>"
                                        class="btn btn-outline-danger btn-action" title="Delete"
                                        onclick="return confirm('Are you sure you want to delete this post? This action cannot be undone.')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Blog posts pagination">
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load additional statistics
        async function loadStats() {
            try {
                const response = await fetch('blog_stats.php');
                const stats = await response.json();

                document.getElementById('publishedCount').textContent = stats.published || '0';
                document.getElementById('draftCount').textContent = stats.drafts || '0';
                document.getElementById('totalViews').textContent = stats.views || '0';
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Load stats on page load
        document.addEventListener('DOMContentLoaded', loadStats);
    </script>
</body>

</html>