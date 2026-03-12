<?php
require_once PROJECT_ROOT . '/config/db.php';

if (
  !isset($_SESSION['user_id']) || ($_SESSION['user_role']) !== 'admin'
) {
  header('Location: /404');
  exit;
}

$uploadDir = 'img/uploads/'; // Исправлено: img/upload вместо img/uploads
$allowedTypes = ['image/jpeg', 'image/png'];
$message = '';
$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) die("Пост не найден");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $author = trim($_POST['author']);
  $desc = trim($_POST['description']);

  $file = $_FILES['img'] ?? null;

  if ($file && $file['error'] === UPLOAD_ERR_OK) {
    // Проверка типа файла
    if (!in_array($file['type'], $allowedTypes)) {
      $message = "Ошибка: Можно загружать только картинки (JPG и PNG)";
    }
    // Проверка размера файла (опционально)
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
        $imgPath = $destination; // Новая картинка
      } else {
        $message = "Не удалось сохранить файл на сервер. Проверьте права на папку $uploadDir";
      }
    }
  }

  // Если нет ошибок, обновляем пост
  if (empty($message)) {
    try {
      // ИСПРАВЛЕНО: правильный синтаксис UPDATE
      $sql = "UPDATE posts SET title = :t, author = :a, description = :d, img_url = :i WHERE id = :id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':t' => $title,
        ':a' => $author,
        ':d' => $desc,
        ':i' => $imgPath,
        ':id' => $id // Добавлен ID для WHERE
      ]);
      $message = 'Пост успешно обновлен!';
      
      // Удаляем старую картинку если была загружена новая
      if ($imgPath !== $post['img_url'] && !empty($post['img_url']) && file_exists($post['img_url'])) {
        unlink($post['img_url']);
      }
      
    } catch (PDOException $e) {
      if (isset($destination) && file_exists($destination)) {
        unlink($destination);
      }
      $message = 'Ошибка БД: ' . $e->getMessage();
    }
  }
}

include PROJECT_ROOT . '/temps/admin/edit.php';
?>