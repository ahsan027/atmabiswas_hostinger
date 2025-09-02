<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login/loging.php");
    exit();
}

// Get blog ID
$blog_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$blog_id) {
    header("Location: blog_manager.php");
    exit();
}

// Include config for paths
require_once '../../config.php';

// Use centralized database connection
include '../Database/db.php';
$db = new Db();
$pdo = $db->connect();

// Get blog post
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE blog_id = ?");
$stmt->execute([$blog_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header("Location: blog_manager.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = trim($_POST['blog_title'] ?? '');
        $content = $_POST['blog_content'] ?? '';
        $summary = $_POST['summary_content'] ?? '';
        $status = $_POST['status'] ?? 'published';

        // Validation
        if (empty($title)) {
            throw new Exception('Blog title is required');
        }

        if (empty($content)) {
            throw new Exception('Blog content is required');
        }

        if (empty($summary)) {
            throw new Exception('Blog summary is required');
        }

        // Update the blog post
        $stmt = $pdo->prepare("
            UPDATE blogs 
            SET blog_title = ?, blog_content = ?, summary = ?, status = ?, updated_at = NOW()
            WHERE blog_id = ?
        ");

        $stmt->execute([$title, $content, $summary, $status, $blog_id]);

        $success_message = "Blog post updated successfully!";

        // Refresh post data
        $stmt = $pdo->prepare("SELECT * FROM blogs WHERE blog_id = ?");
        $stmt->execute([$blog_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog Post - ATMABISWAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --success: #2ecc71;
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
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .editor-content {
            min-height: 300px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 1.5rem;
            background: white;
        }

        .editor-content:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            outline: none;
        }

        .word-count {
            font-size: 0.9rem;
            color: #6c757d;
            text-align: right;
            margin-top: 0.5rem;
        }

        .toolbar {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 2px solid #e9ecef;
        }

        .toolbar .btn {
            margin: 0.2rem;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="fas fa-edit"></i> Edit Blog Post</h1>
                    <p class="mb-0">Update your blog post content</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="blog_manager.php" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left"></i> Back to Manager
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Post Content</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <!-- Blog Title -->
                            <div class="mb-4">
                                <label for="blogTitle" class="form-label">
                                    <i class="fas fa-heading"></i> Blog Title *
                                </label>
                                <input type="text" class="form-control" id="blogTitle" name="blog_title"
                                    value="<?php echo htmlspecialchars($post['blog_title']); ?>"
                                    required maxlength="255">
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <label for="status" class="form-label">
                                    <i class="fas fa-flag"></i> Status
                                </label>
                                <select class="form-select" id="status" name="status">
                                    <option value="published" <?php echo ($post['status'] ?? 'published') === 'published' ? 'selected' : ''; ?>>
                                        Published
                                    </option>
                                    <option value="draft" <?php echo ($post['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>
                                        Draft
                                    </option>
                                </select>
                            </div>

                            <!-- Rich Text Toolbar -->
                            <div class="toolbar">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Formatting buttons -->
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatText('bold')" title="Bold">
                                            <i class="fas fa-bold"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatText('italic')" title="Italic">
                                            <i class="fas fa-italic"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatText('underline')" title="Underline">
                                            <i class="fas fa-underline"></i>
                                        </button>

                                        <!-- Alignment -->
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="alignText('left')" title="Align Left">
                                            <i class="fas fa-align-left"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="alignText('center')" title="Align Center">
                                            <i class="fas fa-align-center"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="alignText('right')" title="Align Right">
                                            <i class="fas fa-align-right"></i>
                                        </button>

                                        <!-- Lists -->
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatText('insertUnorderedList')" title="Bullet List">
                                            <i class="fas fa-list-ul"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="formatText('insertOrderedList')" title="Numbered List">
                                            <i class="fas fa-list-ol"></i>
                                        </button>

                                        <!-- Link -->
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="createLink()" title="Insert Link">
                                            <i class="fas fa-link"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary Section -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-file-alt"></i> Blog Summary *
                                </label>
                                <div id="summaryEditor" contenteditable="true"
                                    class="editor-content"
                                    oninput="updateWordCount('summary')"><?php echo $post['summary'] ?? ''; ?></div>
                                <div id="summaryWordCount" class="word-count">Words: 0</div>
                            </div>

                            <!-- Main Content Section -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-newspaper"></i> Blog Content *
                                </label>
                                <div id="contentEditor" contenteditable="true"
                                    class="editor-content"
                                    oninput="updateWordCount('content')"><?php echo $post['blog_content'] ?? ''; ?></div>
                                <div id="contentWordCount" class="word-count">Words: 0</div>
                            </div>

                            <!-- Hidden inputs for sanitized content -->
                            <input type="hidden" id="sanitizedContent" name="blog_content">
                            <input type="hidden" id="sanitizedSummary" name="summary_content">

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="blog_manager.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Update Post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle"></i> Post Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Author:</strong> <?php echo htmlspecialchars($post['blog_author']); ?></p>
                        <p><strong>Created:</strong> <?php echo date('F j, Y g:i A', strtotime($post['upload_date'])); ?></p>
                        <?php if (isset($post['updated_at']) && $post['updated_at']): ?>
                            <p><strong>Last Updated:</strong> <?php echo date('F j, Y g:i A', strtotime($post['updated_at'])); ?></p>
                        <?php endif; ?>
                        <p><strong>Status:</strong>
                            <span class="badge bg-<?php echo ($post['status'] ?? 'published') === 'published' ? 'success' : 'warning'; ?>">
                                <?php echo ucfirst($post['status'] ?? 'published'); ?>
                            </span>
                        </p>
                        <p><strong>Views:</strong> <?php echo number_format($post['views'] ?? 0); ?></p>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5><i class="fas fa-cogs"></i> Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="blog_content.php?id=<?php echo $post['blog_id']; ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Preview Post
                            </a>
                            <a href="blog_image.php?id=<?php echo $post['blog_id']; ?>" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-image"></i> Manage Images
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let activeEditor = 'content';

        // Get active editor
        function getActiveEditor() {
            const summaryEditor = document.getElementById('summaryEditor');
            const contentEditor = document.getElementById('contentEditor');

            if (document.activeElement === summaryEditor) {
                activeEditor = 'summary';
                return summaryEditor;
            }
            if (document.activeElement === contentEditor) {
                activeEditor = 'content';
                return contentEditor;
            }

            activeEditor = 'content';
            return contentEditor;
        }

        // Formatting functions
        function formatText(command) {
            const editor = getActiveEditor();
            editor.focus();
            try {
                document.execCommand(command, false, null);
                updateWordCount(activeEditor);
            } catch (e) {
                console.error("Formatting failed:", e);
            }
        }

        function alignText(alignType) {
            const editor = getActiveEditor();
            editor.focus();
            try {
                document.execCommand("styleWithCSS", false, true);
                document.execCommand("justify" + alignType.charAt(0).toUpperCase() + alignType.slice(1), false, null);
            } catch (e) {
                console.error("Alignment failed:", e);
            }
        }

        function createLink() {
            const editor = getActiveEditor();
            editor.focus();
            const url = prompt("Enter the URL:");
            if (url) {
                try {
                    document.execCommand("createLink", false, url);
                } catch (e) {
                    console.error("Link creation failed:", e);
                }
            }
        }

        // Word count functions
        function countWords(text) {
            const cleanText = text.replace(/<[^>]*>/g, ' ').trim();
            return cleanText ? cleanText.split(/\s+/).length : 0;
        }

        function updateWordCount(editorType) {
            const editor = editorType === 'summary' ?
                document.getElementById('summaryEditor') :
                document.getElementById('contentEditor');

            const wordCountElement = editorType === 'summary' ?
                document.getElementById('summaryWordCount') :
                document.getElementById('contentWordCount');

            const wordCount = countWords(editor.innerHTML);
            wordCountElement.textContent = `Words: ${wordCount}`;
        }

        // Sanitize HTML content
        function sanitizeHTML(html) {
            const temp = document.createElement('div');
            temp.innerHTML = html;

            const disallowedTags = ['script', 'style', 'iframe', 'object', 'embed'];
            disallowedTags.forEach(tag => {
                const elements = temp.getElementsByTagName(tag);
                while (elements[0]) {
                    const parent = elements[0].parentNode;
                    while (elements[0].firstChild) {
                        parent.insertBefore(elements[0].firstChild, elements[0]);
                    }
                    parent.removeChild(elements[0]);
                }
            });

            return temp.innerHTML;
        }

        // Handle form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            // Sanitize and set hidden inputs
            const summaryContent = document.getElementById('summaryEditor').innerHTML;
            const mainContent = document.getElementById('contentEditor').innerHTML;

            document.getElementById('sanitizedSummary').value = sanitizeHTML(summaryContent);
            document.getElementById('sanitizedContent').value = sanitizeHTML(mainContent);
        });

        // Initialize word counts
        document.addEventListener('DOMContentLoaded', function() {
            updateWordCount('summary');
            updateWordCount('content');
        });
    </script>
</body>

</html>