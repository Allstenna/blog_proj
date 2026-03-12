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
          <a href="/posts">⟵ Вернуться</a>
        </div>
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Пользователь</th>
              <th>Описание</th>
              <th>Дата</th>
              <th>...</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($comments)): ?>
              <tr>
                <td colspan="5" style="text-align: center;">Комментариев нет</td>
              </tr>
            <?php else: ?>
              <?php foreach ($comments as $comment): ?>
                <tr>
                  <td><?= (int)$comment['id'] ?></td>
                  <td><?= htmlspecialchars($comment['username'] ?? 'Аноним') ?></td>
                  <td><?= nl2br(htmlspecialchars($comment['description'])) ?></td>
                  <td><?= date('d.m.y', strtotime($comment['created_at'])) ?></td>
                  <td style="position: relative;">
                    <button class="btn-select" data-comment-id="<?= (int) $comment['id'] ?>"></button>
                    <div class="select-action" data-comment-id="<?= (int) $comment['id'] ?>">
                      <button class="action-btn delete-btn" data-comment-id="<?= (int) $comment['id'] ?>">Удалить</button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>

          </tbody>
        </table>
        <div id="deleteModal" class="modal-overlay">
          <div class="modal-window">
            <button class="modal-close" aria-label="Закрыть"></button>
            <h2 class="modal-title">Удаление комментария</h2>
            <p class="modal-text">Вы точно хотите удалить комментарий?</p>
            <button class="btn-delete-confirm">Удалить</button>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>
<script src="js/selectAction.js"></script>
<script src="js/deleteComment.js"></script>

</html>