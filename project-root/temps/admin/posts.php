<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title>Админка</title>
  <link rel="icon" type="image/png" href="img/favicon.png">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <header>
    <nav>
      <a href="/" class="logo-link">
        <img src="img/logo.svg" alt="FYRRE BLOG" class="logo">
      </a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <button class="btn-logout"><a href="/logout">Выйти</a></button>
      <?php endif; ?>
    </nav>
  </header>
  <main>
    <div class="content">
      <div class="admin-content">
        <div class="link-n-btns">
          <a href="/">⟵ Вернуться</a>
          <div class="btn-admin">
            <button class="add-btn"><a href="/add">Добавить +</a></button>
          </div>
        </div>
        <table class="table">
          <thead>
            <tr>
              <th>Название</th>
              <th>Описание</th>
              <th>Автор</th>
              <th>Дата</th>
              <th>Картинка (url)</th>
              <th>...</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($posts as $post): ?>
              <tr>
                <td><?= htmlspecialchars($post['title']) ?></td>
                <td><?= htmlspecialchars($post['description']) ?></td>
                <td><?= htmlspecialchars($post['author']) ?></td>
                <td><?= $post['created_at'] ?></td>
                <td><?= htmlspecialchars($post['img_url']) ?></td>
                <td style="position: relative;">
                  <button class="btn-select" data-post-id="<?= (int) $post['id'] ?>"></button>
                  <!-- Меню теперь внутри ячейки -->
                  <div class="select-action" data-post-id="<?= (int) $post['id'] ?>">
                    <a href="/edit?id=<?= (int) $post['id'] ?>" class="action-btn">Редактировать</a>
                    <button class="action-btn delete-btn" data-post-id="<?= (int) $post['id'] ?>">Удалить</button>
                    <a href="/comments?id=<?= (int) $post['id'] ?>" class="action-btn">Комментарии</a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div id="deleteModal" class="modal-overlay">
          <div class="modal-window">
            <button class="modal-close" aria-label="Закрыть"></button>
            <h2 class="modal-title">Удаление поста</h2>
            <p class="modal-text">Вы точно хотите удалить данный пост и все комментарии к нему?</p>
            <button class="btn-delete-confirm">Удалить</button>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>
<script src="js/selectAction.js"></script>
<script src="js/deletePost.js"></script>
</html>