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
  $title = trim($_POST['blog_title'] ?? '');
  $content = $_POST['blog_content'] ?? '';
  $summary = $_POST['summary_content'] ?? '';
  $author = $_SESSION['username'] ?? 'ATMABISWAS';

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
        INSERT INTO blogs (blog_title, blog_content, summary, blog_author, upload_date, year)
        VALUES (:title, :content, :summary, :author, NOW(), YEAR(NOW()))
    ");

  $stmt->bindParam(':title', $title, PDO::PARAM_STR);
  $stmt->bindParam(':content', $content, PDO::PARAM_STR);
  $stmt->bindParam(':summary', $summary, PDO::PARAM_STR);
  $stmt->bindParam(':author', $author, PDO::PARAM_STR);

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
