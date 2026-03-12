<?php

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role']) !== 'admin') {
  header('Location: /404');
  exit;
}
require_once PROJECT_ROOT . '/config/db.php';

$sql = "
  SELECT 
    posts.id,
    DATE(posts.created_at) AS created_at,
    posts.author,
    CASE 
      WHEN CHAR_LENGTH(posts.title) > 35 
      THEN CONCAT(LEFT(posts.title, 35), '...') 
      ELSE posts.title 
    END AS title,
    CASE 
      WHEN CHAR_LENGTH(posts.description) > 35 
      THEN CONCAT(LEFT(posts.description, 35), '...') 
      ELSE posts.description 
    END AS description,
    posts.img_url
  FROM posts
  ORDER BY posts.created_at DESC
";

$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll();

include PROJECT_ROOT . '/temps/admin/posts.php';
?>