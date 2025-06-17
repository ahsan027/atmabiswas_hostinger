<?php

$pdo = new PDO('mysql:host=localhost;dbname=atmabiswas;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$blog_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($blog_id) {
  try {

    $stmt = $pdo->prepare("SELECT * FROM blogs WHERE blog_id = ?");
    $stmt->execute([$blog_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    die("Error fetching post: " . $e->getMessage());
  }
} else {
  die("Invalid blog ID");
}
?>

<!DOCTYPE html>
<html>

<head>
  <title><?= htmlspecialchars($post['blog_title']) ?></title>
</head>
<link rel="stylesheet" href="css/blog_content.css">

<body>
  <div class="blog-content">
    <h1><?= htmlspecialchars($post['blog_title']) ?></h1>


    <div class="content-wrapper">
      <?php
      echo htmlspecialchars_decode($post['blog_content']);

      ?>
    </div>

    <div class="post-meta">
      <p>Author: <?= htmlspecialchars($post['blog_author']) ?></p>
      <p>Date: <?= date('F j, Y', strtotime($post['upload_date'])) ?></p>
    </div>
  </div>
</body>

</html>