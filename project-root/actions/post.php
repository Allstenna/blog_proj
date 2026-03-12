<?php
session_start();
require_once PROJECT_ROOT . '/config/db.php';

$post = null;
$comments = [];



function formatTimeAgo($datetime)
{
  $timestamp = strtotime($datetime);
  $diff = time() - $timestamp;

  if ($diff < 60) {
    return 'Только что';
  } elseif ($diff < 3600) {
    $mins = floor($diff / 60);
    return $mins . ' мин' . ($mins == 1 ? '' : 'ут') . ' назад';
  } elseif ($diff < 86400) {
    $hours = floor($diff / 3600);
    return $hours . ' час' . ($hours >= 2 && $hours <= 4 ? 'а' : 'ов') . ' назад';
  } elseif ($diff < 2592000) {
    $days = floor($diff / 86400);
    return $days . ' дн' . ($days == 1 ? '' : 'я') . ' назад';
  } else {
    return date('d.m.Y', $timestamp);
  }
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$sql = "
    SELECT id, title, description, author, img_url, created_at
    FROM posts
    WHERE id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$post = $stmt->fetch();

if ($post) {
  setlocale(LC_TIME, 'ru_RU.UTF-8', 'rus_RUS.UTF-8', 'Russian_Russia.1251');
  $timestamp = strtotime($post['created_at']);
  $post['created_at_formatted'] = trim(strftime("%e %B, %Y", $timestamp));

  $sqlComments = "
        SELECT 
            c.id,
            c.description,
            c.created_at,
            u.username
        FROM comments c
        LEFT JOIN users u ON c.user_id = u.id
        WHERE c.post_id = ?
        ORDER BY c.created_at ASC
    ";
  $stmtComments = $pdo->prepare($sqlComments);
  $stmtComments->execute([$id]);
  $comments = $stmtComments->fetchAll(PDO::FETCH_ASSOC);
}

// Подключаем шаблон
include PROJECT_ROOT . '/temps/post.php';
?>