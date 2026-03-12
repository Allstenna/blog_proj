<?php
session_start();
require_once PROJECT_ROOT . '/config/db.php';

header('Content-Type: application/json');

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'Необходимо авторизоваться']);
  exit;
}

// Проверка метода
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success' => false, 'message' => 'Неверный метод запроса']);
  exit;
}

// Получаем данные
$post_id = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
$user_id = isset($_POST['user_id']) ? (int) $_POST['user_id'] : $_SESSION['user_id'];
$comment_text = isset($_POST['comment_text']) ? trim($_POST['comment_text']) : '';

// Валидация
if ($post_id <= 0) {
  echo json_encode(['success' => false, 'message' => 'Неверный ID поста']);
  exit;
}

if ($user_id <= 0) {
  echo json_encode(['success' => false, 'message' => 'Неверный ID пользователя']);
  exit;
}

if (empty($comment_text)) {
  echo json_encode(['success' => false, 'message' => 'Введите текст комментария']);
  exit;
}

if (mb_strlen($comment_text, 'UTF-8') > 1000) {
  echo json_encode(['success' => false, 'message' => 'Комментарий слишком длинный (макс. 1000 символов)']);
  exit;
}

// Проверка существования поста
$stmt = $pdo->prepare("SELECT id FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
if (!$stmt->fetch()) {
  echo json_encode(['success' => false, 'message' => 'Пост не найден']);
  exit;
}

// Добавляем комментарий
try {
  $sql = "INSERT INTO comments (post_id, user_id, description, created_at) 
          VALUES (?, ?, ?, NOW())";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$post_id, $user_id, $comment_text]);
  $comment_id = $pdo->lastInsertId();

  // Получаем данные пользователя
  $userStmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
  $userStmt->execute([$user_id]);
  $user = $userStmt->fetch(PDO::FETCH_ASSOC);

  // Форматируем время (функция formatTimeAgo из post.php)
  function formatTimeAgo($datetime) {
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

  echo json_encode([
    'success' => true,
    'comment_id' => $comment_id,
    'username' => $user['username'] ?? 'Аноним',
    'comment_text' => $comment_text,
    'time_ago' => formatTimeAgo(date('Y-m-d H:i:s'))
  ]);

} catch (PDOException $e) {
  error_log($e->getMessage());
  echo json_encode(['success' => false, 'message' => 'Ошибка базы данных']);
}

?>