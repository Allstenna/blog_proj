<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header('Location: /404');
  exit;
}

require_once PROJECT_ROOT . '/config/db.php';

$post_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);


// Получаем комментарии с данными пользователя
$sql = "
    SELECT 
        c.id,
        c.description,
        c.created_at,
        u.username
    FROM comments c
    LEFT JOIN users u ON c.user_id = u.id
    WHERE c.post_id = ?
    ORDER BY c.created_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$post_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

include PROJECT_ROOT . '/temps/admin/comments.php';
?>