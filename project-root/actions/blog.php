<?php
session_start();
require_once PROJECT_ROOT . '/config/db.php';

setlocale(LC_TIME, 'ru_RU.UTF-8', 'rus_RUS.UTF-8', 'Russian_Russia.1251');

$errorMsg = '';
$successMsg = '';

// Настройки пагинации
$posts_per_page = 4;
$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($current_page < 1)
  $current_page = 1;

$offset = ($current_page - 1) * $posts_per_page;

// 🔹 Получаем общее количество постов для расчёта страниц
$total_sql = "SELECT COUNT(*) as total FROM posts";
$total_stmt = $pdo->query($total_sql);
$total_posts = $total_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_posts / $posts_per_page);;

// 🔹 Получаем посты для текущей страницы
$sql = "
SELECT
  id,
  title, 
  description, 
  author, 
  img_url, 
  created_at
FROM posts 
ORDER BY created_at DESC
LIMIT :limit OFFSET :offset
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $posts_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

// Форматируем даты для каждого поста
foreach ($posts as &$post) {
  $timestamp = strtotime($post['created_at']);
  $formatted = strftime('%e %B, %Y', $timestamp);
  $post['created_at_formatted'] = trim($formatted);
}
unset($post);

// Передаем переменные пагинации в шаблон
include PROJECT_ROOT . '/temps/blog.php';
?>