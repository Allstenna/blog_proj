<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($post['title']) ?></title>
  <link rel="icon" type="image/png" href="img/favicon.png">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <header>
    <nav>
      <a href="/" class="logo-link">
        <img src="img/logo.svg" alt="FYRRE BLOG" class="logo">
      </a>
      <?php if (!isset($_SESSION['user_id'])): ?>
        <button class="btn-logout"><a href="/log">Войти</a></button>
      <?php else: ?>
        <button class="btn-logout"><a href="/logout">Выйти</a></button>
      <?php endif; ?>
    </nav>
  </header>
  <div class="content">
    <div class="content-wrap">
      <div class="post-main">
        <div class="post-image">
          <img src="<?= htmlspecialchars($post['img_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
        </div>
        <div class="post-interaction">
          <h1><?= htmlspecialchars($post['title']) ?></h1>
          <div class="post-react">
            <button id="btn-post-saved"></button>
            <button id="btn-post-like"></button>
          </div>
        </div>
        <div class="post-meta">
          <div class="text-author">
            <span>Текст</span>
            <p class="author-name"><?= htmlspecialchars($post['author']) ?></p>
          </div>
          <p class="text-date"><?= htmlspecialchars($post['created_at_formatted']) ?></p>
        </div>
        <div class="post-content">
          <p><?= htmlspecialchars($post['description']) ?></p>
        </div>
      </div>
      <aside class="comments-section">
        <h2>Комментарии</h2>
        <?php if (!isset($_SESSION['user_id'])): ?>
          <button class="comment-input">
            <a href="/log">Чтобы оставить комментарий, войдите</a>
          </button>
        <?php endif; ?>
        <div class="comments-list">
          <?php if (isset($_SESSION['user_id'])): ?>
            <div class="add-comment-section">
              <form action="/add_comment" method="POST" class="form-add-comment" id="commentForm">
                <input type="hidden" name="post_id" value="<?= (int) $post['id'] ?>">
                <input type="hidden" name="user_id" value="<?= (int) $_SESSION['user_id'] ?>">
                <div class="comment-avatar"></div>
                <input type="text" name="comment_text" id="commentText" class="form-control" placeholder="Комментарий..." required>
                <button type="submit" class="btn-add-comment"></button>
              </form>
              <div id="commentMessage" class="comment-message"></div>
            </div>
          <?php endif; ?>

          <div class="comment" id="commentsList">
            <?php if (empty($comments)): ?>
              <p class="no-comments">Будьте первым, кто оставит комментарий!</p>
            <?php else: ?>
              <?php foreach ($comments as $comment): ?>
                <div class="comment-contain">
                  <div class="comment-avatar"></div>
                  <div class="comment-content" data-comment-id="<?= (int) $comment['id'] ?>">
                    <div class="user-date">
                      <p class="comment-author">
                        <?= htmlspecialchars($comment['username'] ?? 'Аноним') ?>
                      </p>
                      <p class="comment-time">
                        <?= formatTimeAgo($comment['created_at']) ?>
                      </p>
                    </div>
                    <p class="comment-info">
                      <?= nl2br(htmlspecialchars($comment['description'])) ?>
                    </p>
                    <div class="comment-interaction">
                      <button class="btn-comment btn-like" data-comment-id="<?= (int) $comment['id'] ?>"
                        aria-label="Нравится"></button>
                      <button class="btn-comment btn-dislike" data-comment-id="<?= (int) $comment['id'] ?>"
                        aria-label="Не нравится"></button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </aside>
    </div>
  </div>
</body>
<script src="js/ajax.js"></script>
<script src="js/btnPost.js"></script>

</html>