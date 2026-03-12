<?php
session_start();
require_once PROJECT_ROOT . '/config/db.php';

$errorMsg = '';
$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $pass = $_POST['password'];

  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if ($user && password_verify($pass, $user['password_hash'])) {

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = $user['role'];

    if ($user['role'] === 'admin') {
      header("Location: /posts");
    } else {
      header("Location: /");
    }
    exit;
  } else {
    $errorMsg = "Неверный логин или пароль";
  }
}
include PROJECT_ROOT . '/temps/log.php';
?>