<?php
// 1. Подключаем настройки БД (создан на прошлом занятии 14.01)
require_once PROJECT_ROOT . '/config/db.php';

$errorMsg = '';
$successMsg = '';

// 2. Проверяем, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Очистка данных
  $email = trim($_POST['email']);
  $pass = $_POST['password'];
  $passConfirm = $_POST['password_confirm'];

  // 3. Валидация (Проверки)
  if (empty($email) || empty($pass)) {
    $errorMsg = "Заполните все поля!";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errorMsg = "Некорректный формат Email!";
  } elseif (mb_strlen($pass) < 6) {
    $errorMsg = "Пароль должен содержать не менее 6 символов!";
  } elseif ($pass !== $passConfirm) {
    $errorMsg = "Пароли не совпадают!";
  } else {
    // 4. Если ошибок нет — ХЕШИРУЕМ и СОХРАНЯЕМ

    // Генерируем безопасный хеш (bcrypt)
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    // Готовим SQL-запрос (Защита от SQL Injection)
    $sql = "INSERT INTO users (email, password_hash, role) VALUES (:email, :hash, 'client')";
    $stmt = $pdo->prepare($sql);

    try {
      $stmt->execute([
        ':email' => $email,
        ':hash' => $hash
      ]);
      $successMsg = "Регистрация успешна!";
    } catch (PDOException $e) {
      // Код 23000 означает нарушение уникальности (дубликат email)
      if ($e->getCode() == 23000) {
        $errorMsg = "Такой email уже зарегистрирован.";
      } else {
        $errorMsg = "Ошибка БД: " . $e->getMessage();
      }
    }
  }
}
include PROJECT_ROOT . '/temps/reg.php';
?>