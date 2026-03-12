<?php
session_start();
require_once PROJECT_ROOT . '/config/db.php';
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role']) !== 'admin') {
  header('Location: /404');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$id]);
}
?>