<?php
session_start();
require_once PROJECT_ROOT . '/config/db.php';

$message = '';
$uploadDir = 'img/uploads/'; // Убедитесь, что папка создана и имеет права 755/775
$allowedTypes = ['image/jpeg', 'image/png'];

// Проверка прав доступа
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header('Location: /404');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $author = trim($_POST['author']);
  $desc = trim($_POST['description']);

  $file = $_FILES['img'] ?? null;

  if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
    $message = "Ошибка загрузки файла. Код ошибки: " . ($file['error'] ?? 'неизвестно');
  }
  // Проверка типа файла
  elseif (!in_array($file['type'], $allowedTypes)) {
    $message = "Ошибка: Можно загружать только картинки (JPG и PNG)";
  }
  // Проверка размера файла
  else {
    // Генерация уникального имени файла
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = uniqid('img_', true) . '.' . $extension;
    $destination = $uploadDir . $newName;

    // Создаём папку, если не существует
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0755, true);
    }

    // Перемещение файла
    if (move_uploaded_file($file['tmp_name'], $destination)) {
      $imgPath = $uploadDir . $newName;

      try {
        $sql = "INSERT INTO posts (title, author, description, img_url) VALUES (:t, :a, :d, :i)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
          ':t' => $title,
          ':a' => $author,
          ':d' => $desc,
          ':i' => $imgPath
        ]);
        $message = 'Пост успешно добавлен!';
      } catch (PDOException $e) {
        if (file_exists($destination)) {
          unlink($destination);
        }
        $message = 'Ошибка БД: ' . $e->getMessage();
      }
    } else {
      $message = "Не удалось сохранить файл на сервер. Проверьте права на папку $uploadDir";
    }
  }
}
include PROJECT_ROOT . '/temps/admin/add.php';
?>