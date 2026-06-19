<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
  http_response_code(401);
  echo json_encode([
    'status' => 'error',
    'message' => 'Unauthorized access. Please login first.'
  ]);
  exit();
}

header('Content-Type: application/json');

try {
  // Use the centralized database connection
  include 'Database/db.php';
  $db = new Db();
  $pdo = $db->connect();

  // Validate and sanitize input
  $title       = trim($_POST['blog_title']    ?? '');
  $content     = $_POST['blog_content']       ?? '';
  $summary     = $_POST['summary_content']    ?? '';
  $author      = $_SESSION['username']        ?? 'ATMABISWAS';
  $category    = trim($_POST['category']      ?? 'news');
  $source_link = trim($_POST['source_link']   ?? '');
  $tags        = trim($_POST['tags']          ?? '');
  $seo_title   = trim($_POST['seo_title']     ?? '');
  $seo_desc    = trim($_POST['seo_description'] ?? '');
  $seo_keys    = trim($_POST['seo_keywords']  ?? '');
  $social_img  = trim($_POST['social_image']  ?? '');
  $featured    = isset($_POST['featured']) ? 1 : 0;

  // Auto-generate slug from title if not provided
  $slug = trim($_POST['slug'] ?? '');
  if ($slug === '') {
      $slug = mb_strtolower($title, 'UTF-8');
      $slug = preg_replace('/[^a-z0-9\s-]/u', '', $slug);
      $slug = preg_replace('/\s+/', '-', trim($slug));
      $slug = preg_replace('/-+/', '-', $slug);
      $slug = trim($slug, '-');
  }
  // Ensure slug uniqueness by appending timestamp if needed
  if ($slug) $slug = $slug . '-' . time();

  // Calculate reading time (words ÷ 200 wpm)
  $reading_time = max(1, (int)ceil(str_word_count(strip_tags($content)) / 200));

  $allowed_categories = ['news', 'media', 'announcement', 'press'];
  if (!in_array($category, $allowed_categories, true)) $category = 'news';

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

  // Additional security: limit content length
  if (strlen($title) > 255) {
    throw new Exception('Title is too long (max 255 characters)');
  }

  if (strlen($content) > 50000) {
    throw new Exception('Content is too long (max 50,000 characters)');
  }

  if (strlen($summary) > 1000) {
    throw new Exception('Summary is too long (max 1,000 characters)');
  }

  $stmt = $pdo->prepare("
        INSERT INTO blogs
            (blog_title, slug, blog_content, summary, blog_author, upload_date, year,
             category, source_link, tags, seo_title, seo_description, seo_keywords,
             social_image, featured, reading_time)
        VALUES
            (:title, :slug, :content, :summary, :author, NOW(), YEAR(NOW()),
             :category, :source_link, :tags, :seo_title, :seo_desc, :seo_keys,
             :social_img, :featured, :reading_time)
    ");

  $stmt->bindParam(':title',        $title,        PDO::PARAM_STR);
  $stmt->bindParam(':slug',         $slug,         PDO::PARAM_STR);
  $stmt->bindParam(':content',      $content,      PDO::PARAM_STR);
  $stmt->bindParam(':summary',      $summary,      PDO::PARAM_STR);
  $stmt->bindParam(':author',       $author,       PDO::PARAM_STR);
  $stmt->bindParam(':category',     $category,     PDO::PARAM_STR);
  $stmt->bindParam(':source_link',  $source_link,  PDO::PARAM_STR);
  $stmt->bindParam(':tags',         $tags,         PDO::PARAM_STR);
  $stmt->bindParam(':seo_title',    $seo_title,    PDO::PARAM_STR);
  $stmt->bindParam(':seo_desc',     $seo_desc,     PDO::PARAM_STR);
  $stmt->bindParam(':seo_keys',     $seo_keys,     PDO::PARAM_STR);
  $stmt->bindParam(':social_img',   $social_img,   PDO::PARAM_STR);
  $stmt->bindParam(':featured',     $featured,     PDO::PARAM_INT);
  $stmt->bindParam(':reading_time', $reading_time, PDO::PARAM_INT);

  if ($stmt->execute()) {
    echo json_encode([
      'status'   => 'success',
      'message'  => 'Blog post saved successfully!',
      'post_id'  => $pdo->lastInsertId()
    ]);
  } else {
    echo json_encode([
      'status'  => 'error',
      'message' => 'Failed to save blog post'
    ]);
  }
} catch (PDOException $e) {
  error_log('Database Error: ' . $e->getMessage());
  echo json_encode([
    'status'  => 'error',
    'message' => 'Database error occurred',
    'error'   => $e->getMessage()
  ]);
} catch (Exception $e) {
  error_log('General Error: ' . $e->getMessage());
  echo json_encode([
    'status'  => 'error',
    'message' => 'An error occurred',
    'error'   => $e->getMessage()
  ]);
}
