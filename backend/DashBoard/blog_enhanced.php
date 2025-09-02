<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login/loging.php");
    exit();
}

// Include config for paths
require_once '../../config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ATMABISWAS - Enhanced Blog Editor</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="icon" type="image/png" href="../images/logo/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #000000;
            --secondary: #0073e6;
            --accent: #0073e6;
            --success: #0073e6;
            --warning: #0073e6;
            --light: #ffffff;
            --dark: #000000;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: #ffffff;
            min-height: 100vh;
        }

        .main-header {
            background: #0073e6;
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
        }

        .main-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .card-header {
            background: #ffffff;
            color: #000000;
            border: 2px solid #000000;
            padding: 1rem 1.5rem;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 2px solid #000000;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0073e6;
            box-shadow: 0 0 0 0.2rem rgba(0, 115, 230, 0.25);
        }

        .toolbar {
            background: #ffffff;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 2px solid #000000;
        }

        .toolbar .btn {
            margin: 0.2rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .toolbar .btn:hover {
            transform: translateY(-1px);
        }

        .editor-content {
            min-height: 400px;
            border: 2px solid #000000;
            border-radius: 8px;
            padding: 1.5rem;
            background: white;
            transition: all 0.3s ease;
        }

        .editor-content:focus {
            border-color: #0073e6;
            box-shadow: 0 0 0 0.2rem rgba(0, 115, 230, 0.25);
            outline: none;
        }

        .editor-content[contenteditable="true"]:empty:before {
            content: attr(placeholder);
            color: #000000;
            font-style: italic;
        }

        .btn-publish {
            background: #0073e6;
            border: none;
            border-radius: 10px;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-publish:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 115, 230, 0.3);
            color: white;
        }

        .word-count {
            font-size: 0.9rem;
            color: #000000;
            text-align: right;
            margin-top: 0.5rem;
        }

        .word-count.low {
            color: #0073e6;
            font-weight: 600;
        }

        .drag-over {
            background-color: rgba(0, 115, 230, 0.1) !important;
            border: 2px dashed #0073e6 !important;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            background: #0073e6;
            color: white;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            transform: translateX(200%);
            transition: transform 0.3s ease;
            z-index: 1050;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.error {
            background: #0073e6;
        }

        .notification.warning {
            background: #0073e6;
        }

        .preview-mode {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .preview-mode h1 {
            color: #000000;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #0073e6;
        }

        .preview-mode .meta {
            color: #000000;
            font-size: 0.9rem;
            margin-bottom: 2rem;
            padding: 1rem;
            background: #ffffff;
            border-radius: 8px;
        }

        .tabs {
            border-bottom: 2px solid #000000;
            margin-bottom: 2rem;
        }

        .tab-button {
            background: none;
            border: none;
            padding: 1rem 2rem;
            font-weight: 600;
            color: #000000;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .tab-button.active {
            color: #000000;
            border-bottom-color: #000000;
            background-color: #ffffff;
        }

        .tab-button:hover {
            color: #000000;
            background-color: #ffffff;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        .loading.show {
            display: block;
        }

        .spinner {
            width: 3rem;
            height: 3rem;
            border: 3px solid #000000;
            border-top: 3px solid #0073e6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }



        /* Override button colors to use only #0073e6, white, and black */
        .btn-outline-primary {
            color: #0073e6;
            border-color: #0073e6;
        }

        .btn-outline-primary:hover {
            background-color: #0073e6;
            color: white;
        }

        .btn-outline-success {
            color: #0073e6;
            border-color: #0073e6;
        }

        .btn-outline-success:hover {
            background-color: #0073e6;
            color: white;
        }

        .btn-outline-info {
            color: #0073e6;
            border-color: #0073e6;
        }

        .btn-outline-info:hover {
            background-color: #0073e6;
            color: white;
        }

        .btn-outline-secondary {
            color: #000000;
            border-color: #000000;
        }

        .btn-outline-secondary:hover {
            background-color: #000000;
            color: white;
        }

        .text-success {
            color: #0073e6 !important;
        }

        .text-danger {
            color: #000000 !important;
        }

        .text-muted {
            color: #000000 !important;
        }

        .badge.bg-success {
            background-color: #0073e6 !important;
        }

        .badge.bg-warning {
            background-color: #0073e6 !important;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="fas fa-pen-fancy"></i> Enhanced Blog Editor</h1>
                    <p class="mb-0">Create and manage your blog posts with advanced features</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="dashboard.php" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">


        <!-- Main Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="tabs">
                            <button class="tab-button active" onclick="showTab('editor')">
                                <i class="fas fa-edit"></i> Editor
                            </button>
                            <button class="tab-button" onclick="showTab('preview')">
                                <i class="fas fa-eye"></i> Preview
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Editor Tab -->
                        <div id="editor-tab" class="tab-content">
                            <form id="blogForm" action="../blogUpload_process.php" method="POST">
                                <!-- Blog Title -->
                                <div class="mb-4">
                                    <label for="blogTitle" class="form-label">
                                        <i class="fas fa-heading"></i> Blog Title *
                                    </label>
                                    <input type="text" class="form-control" id="blogTitle" name="blog_title"
                                        placeholder="Enter an engaging title..." required maxlength="255">
                                    <div class="form-text">
                                        <span id="titleCount">0</span>/255 characters
                                    </div>
                                </div>

                                <!-- Rich Text Toolbar -->
                                <div class="toolbar">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select class="form-select form-select-sm d-inline-block w-auto" onchange="changeFont(this.value)">
                                                <option value="Arial">Arial</option>
                                                <option value="Times New Roman">Times New Roman</option>
                                                <option value="Georgia">Georgia</option>
                                                <option value="Courier New">Courier New</option>
                                                <option value="Verdana">Verdana</option>
                                            </select>

                                            <select class="form-select form-select-sm d-inline-block w-auto" onchange="changeFontSize(this.value)">
                                                <option value="1">Small</option>
                                                <option value="2">Medium</option>
                                                <option value="3" selected>Large</option>
                                                <option value="4">Extra Large</option>
                                                <option value="5">XXL</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
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

                                            <input type="color" class="form-control form-control-sm d-inline-block w-auto" onchange="changeColor(this.value)" title="Text Color">

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

                                            <!-- Link & Image -->
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="createLink()" title="Insert Link">
                                                <i class="fas fa-link"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="triggerUpload()" title="Insert Image">
                                                <i class="fas fa-image"></i>
                                            </button>
                                            <input type="file" id="imageUpload" style="display: none;" accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                <!-- Summary Section -->
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="fas fa-file-alt"></i> Blog Summary *
                                    </label>
                                    <div id="summaryEditor" contenteditable="true"
                                        placeholder="Write a compelling summary (100-1000 characters recommended)..."
                                        class="editor-content"
                                        ondrop="handleDrop(event)"
                                        ondragover="handleDragOver(event)"
                                        ondragleave="handleDragLeave(event)"
                                        oninput="updateWordCount('summary')">
                                    </div>
                                    <div id="summaryWordCount" class="word-count">Words: 0 | Characters: 0</div>
                                </div>

                                <!-- Main Content Section -->
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="fas fa-newspaper"></i> Blog Content *
                                    </label>
                                    <div id="contentEditor" contenteditable="true"
                                        placeholder="Start writing your blog post here..."
                                        class="editor-content"
                                        ondrop="handleDrop(event)"
                                        ondragover="handleDragOver(event)"
                                        ondragleave="handleDragLeave(event)"
                                        oninput="updateWordCount('content')">
                                    </div>
                                    <div id="contentWordCount" class="word-count">Words: 0 | Characters: 0</div>
                                </div>

                                <!-- Hidden inputs for sanitized content -->
                                <input type="hidden" id="sanitizedContent" name="blog_content">
                                <input type="hidden" id="sanitizedSummary" name="summary_content">
                                <input type="hidden" id="postStatus" name="status" value="published">

                                <!-- Action Buttons -->
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-outline-secondary" onclick="saveDraft()">
                                        <i class="fas fa-save"></i> Save Draft
                                    </button>
                                    <button type="submit" class="btn btn-publish">
                                        <i class="fas fa-paper-plane"></i> Publish Post
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Preview Tab -->
                        <div id="preview-tab" class="tab-content" style="display: none;">
                            <div class="preview-mode">
                                <h1 id="previewTitle">Blog Title</h1>
                                <div class="meta">
                                    <i class="fas fa-user"></i> By <?php echo htmlspecialchars($_SESSION['username']); ?> |
                                    <i class="fas fa-calendar"></i> <span id="previewDate"><?php echo date('F j, Y'); ?></span>
                                </div>

                                <div class="mb-4">
                                    <h5>Summary:</h5>
                                    <div id="previewSummary" class="text-muted">Blog summary will appear here...</div>
                                </div>

                                <div id="previewContent">Blog content will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading">
        <div class="spinner"></div>
        <p>Processing your request...</p>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification"></div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variables
        let activeEditor = 'content';
        let isPreviewMode = false;

        // Initialize the application
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            updateWordCount('summary');
            updateWordCount('content');
        });

        // Setup event listeners
        function setupEventListeners() {
            // Title character count
            document.getElementById('blogTitle').addEventListener('input', function() {
                const count = this.value.length;
                document.getElementById('titleCount').textContent = count;

                // Update preview
                document.getElementById('previewTitle').textContent = this.value || 'Blog Title';
            });

            // Form submission
            document.getElementById('blogForm').addEventListener('submit', handleFormSubmit);

            // Image upload
            document.getElementById('imageUpload').addEventListener('change', function(e) {
                handleImage(e.target.files[0]);
            });

            // Auto-save (every 30 seconds)
            setInterval(autoSave, 30000);
        }

        // Tab management
        function showTab(tabName) {
            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            // Show/hide content
            document.getElementById('editor-tab').style.display = tabName === 'editor' ? 'block' : 'none';
            document.getElementById('preview-tab').style.display = tabName === 'preview' ? 'block' : 'none';

            if (tabName === 'preview') {
                updatePreview();
            }
        }

        // Update preview
        function updatePreview() {
            const title = document.getElementById('blogTitle').value || 'Blog Title';
            const summary = document.getElementById('summaryEditor').innerHTML || 'Blog summary will appear here...';
            const content = document.getElementById('contentEditor').innerHTML || 'Blog content will appear here...';

            document.getElementById('previewTitle').textContent = title;
            document.getElementById('previewSummary').innerHTML = summary;
            document.getElementById('previewContent').innerHTML = content;
        }

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

            // Default to content editor
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
                showNotification("Formatting failed. Please try again.", 'error');
            }
        }

        function changeColor(color) {
            const editor = getActiveEditor();
            editor.focus();

            try {
                document.execCommand("styleWithCSS", false, true);
                document.execCommand("foreColor", false, color);
            } catch (e) {
                console.error("Color change failed:", e);
                showNotification("Color change failed. Please try again.", 'error');
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
                showNotification("Alignment failed. Please try again.", 'error');
            }
        }

        function changeFont(font) {
            const editor = getActiveEditor();
            editor.focus();

            try {
                document.execCommand("fontName", false, font);
            } catch (e) {
                console.error("Font change failed:", e);
                showNotification("Font change failed. Please try again.", 'error');
            }
        }

        function changeFontSize(size) {
            const editor = getActiveEditor();
            editor.focus();

            try {
                document.execCommand("fontSize", false, size);
            } catch (e) {
                console.error("Font size change failed:", e);
                showNotification("Font size change failed. Please try again.", 'error');
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
                    showNotification("Link creation failed. Please try again.", 'error');
                }
            }
        }

        function triggerUpload() {
            document.getElementById("imageUpload").click();
        }

        // Image handling
        function handleImage(file) {
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                showNotification('Please select a valid image file.', 'error');
                return;
            }

            if (file.size > 5 * 1024 * 1024) { // 5MB limit
                showNotification('Image size should be less than 5MB.', 'error');
                return;
            }

            const editor = getActiveEditor();
            editor.focus();

            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement("img");
                img.src = e.target.result;
                img.style.maxWidth = "100%";
                img.style.borderRadius = "8px";
                img.style.margin = "15px 0";
                img.style.display = "block";

                // Insert at cursor position
                const selection = window.getSelection();
                if (selection.rangeCount > 0) {
                    const range = selection.getRangeAt(0);
                    range.insertNode(img);

                    // Move cursor after the image
                    const newRange = document.createRange();
                    newRange.setStartAfter(img);
                    newRange.collapse(true);
                    selection.removeAllRanges();
                    selection.addRange(newRange);
                } else {
                    editor.appendChild(img);
                }

                updateWordCount(activeEditor);
            };
            reader.readAsDataURL(file);
        }

        // Drag and drop handlers
        function handleDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
            e.target.classList.add("drag-over");
        }

        function handleDragLeave(e) {
            e.preventDefault();
            e.stopPropagation();
            e.target.classList.remove("drag-over");
        }

        function handleDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            e.target.classList.remove("drag-over");

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                if (file && file.type.startsWith("image/")) {
                    handleImage(file);
                }
            }
        }

        // Word count functions
        function countWords(text) {
            const cleanText = text.replace(/<[^>]*>/g, ' ').trim();
            const words = cleanText ? cleanText.split(/\s+/).length : 0;
            const characters = cleanText.length;
            return {
                words,
                characters
            };
        }

        function updateWordCount(editorType) {
            const editor = editorType === 'summary' ?
                document.getElementById('summaryEditor') :
                document.getElementById('contentEditor');

            const wordCountElement = editorType === 'summary' ?
                document.getElementById('summaryWordCount') :
                document.getElementById('contentWordCount');

            const {
                words,
                characters
            } = countWords(editor.innerHTML);
            wordCountElement.textContent = `Words: ${words} | Characters: ${characters}`;

            // Update preview if visible
            if (!document.getElementById('preview-tab').style.display === 'none') {
                updatePreview();
            }

            // Highlight if summary is too short
            if (editorType === 'summary') {
                if (characters < 100) {
                    wordCountElement.classList.add('low');
                } else {
                    wordCountElement.classList.remove('low');
                }
            }
        }

        // Form handling
        function sanitizeHTML(html) {
            const temp = document.createElement('div');
            temp.innerHTML = html;

            // Remove disallowed tags but keep their content
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

        async function handleFormSubmit(e) {
            e.preventDefault();

            // Validation
            const title = document.getElementById('blogTitle').value.trim();
            const summaryContent = document.getElementById('summaryEditor').innerHTML.trim();
            const mainContent = document.getElementById('contentEditor').innerHTML.trim();

            if (!title) {
                showNotification('Please enter a blog title.', 'error');
                return;
            }

            if (!summaryContent || summaryContent === '<br>') {
                showNotification('Please write a summary for your blog.', 'error');
                return;
            }

            if (!mainContent || mainContent === '<br>') {
                showNotification('Please write the main content for your blog.', 'error');
                return;
            }

            // Show loading
            showLoading(true);

            // Sanitize and set hidden inputs
            document.getElementById('sanitizedContent').value = sanitizeHTML(mainContent);
            document.getElementById('sanitizedSummary').value = sanitizeHTML(summaryContent);

            // Submit form
            const formData = new FormData(e.target);

            try {
                const response = await fetch(e.target.action, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    showNotification('Blog published successfully! Redirecting...', 'success');
                    setTimeout(() => {
                        window.location.href = 'blog_image.php?id=' + result.post_id;
                    }, 2000);
                } else {
                    throw new Error(result.message || 'Unknown error occurred');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error publishing post: ' + error.message, 'error');
            } finally {
                showLoading(false);
            }
        }

        // Save draft
        async function saveDraft() {
            const title = document.getElementById('blogTitle').value.trim();

            if (!title) {
                showNotification('Please enter a title before saving draft.', 'warning');
                return;
            }

            document.getElementById('postStatus').value = 'draft';

            // Temporarily change form action for draft save
            const form = document.getElementById('blogForm');
            const originalAction = form.action;

            showNotification('Saving draft...', 'info');

            try {
                await handleFormSubmit({
                    preventDefault: () => {},
                    target: form
                });
                showNotification('Draft saved successfully!', 'success');
            } catch (error) {
                showNotification('Error saving draft: ' + error.message, 'error');
            } finally {
                document.getElementById('postStatus').value = 'published';
                form.action = originalAction;
            }
        }

        // Auto-save function
        function autoSave() {
            const title = document.getElementById('blogTitle').value.trim();
            const summaryContent = document.getElementById('summaryEditor').innerHTML.trim();
            const mainContent = document.getElementById('contentEditor').innerHTML.trim();

            if (title && (summaryContent || mainContent)) {
                // Save to localStorage as backup
                const autoSaveData = {
                    title: title,
                    summary: summaryContent,
                    content: mainContent,
                    timestamp: new Date().toISOString()
                };

                localStorage.setItem('blogAutoSave', JSON.stringify(autoSaveData));
                console.log('Auto-saved at', new Date().toLocaleTimeString());
            }
        }

        // Load auto-saved data
        function loadAutoSave() {
            const autoSaveData = localStorage.getItem('blogAutoSave');
            if (autoSaveData) {
                const data = JSON.parse(autoSaveData);
                const timestamp = new Date(data.timestamp);
                const now = new Date();
                const hoursDiff = (now - timestamp) / (1000 * 60 * 60);

                if (hoursDiff < 24) { // Only load if less than 24 hours old
                    if (confirm(`Auto-saved content found from ${timestamp.toLocaleString()}. Would you like to restore it?`)) {
                        document.getElementById('blogTitle').value = data.title;
                        document.getElementById('summaryEditor').innerHTML = data.summary;
                        document.getElementById('contentEditor').innerHTML = data.content;

                        updateWordCount('summary');
                        updateWordCount('content');

                        showNotification('Auto-saved content restored!', 'success');
                    }
                }
            }
        }





        // Utility functions
        function showLoading(show) {
            const overlay = document.getElementById('loadingOverlay');
            if (show) {
                overlay.classList.add('show');
            } else {
                overlay.classList.remove('show');
            }
        }

        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.className = `notification ${type} show`;

            setTimeout(() => {
                notification.classList.remove('show');
            }, 5000);
        }



        // Load auto-save on page load
        window.addEventListener('load', function() {
            setTimeout(loadAutoSave, 1000); // Delay to ensure page is fully loaded
        });

        // Warn before leaving with unsaved changes
        window.addEventListener('beforeunload', function(e) {
            const title = document.getElementById('blogTitle').value.trim();
            const summaryContent = document.getElementById('summaryEditor').innerHTML.trim();
            const mainContent = document.getElementById('contentEditor').innerHTML.trim();

            if (title || summaryContent || mainContent) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    </script>
</body>

</html>