<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title>Блог</title>
  <link rel="icon" type="image/png" href="img/favicon.png">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <?php if ($errorMsg): ?>
    <div class=""><?= $errorMsg ?></div>
  <?php endif; ?>

  <?php if ($successMsg): ?>
    <div class=""><?= $successMsg ?></div>
  <?php endif; ?>
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
  <main>
    <div class="content">
      <h1 class="h1-blog">БЛОГ</h1>
      <div class="slider-post">
        <?php if (count($posts) > 0): ?>
          <?php foreach ($posts as $post): ?>
            <?php
            $description = $post['description'];
            $limit = 200;
            if (mb_strlen($description, 'UTF-8') > $limit) {
              $description = mb_substr($description, 0, $limit, 'UTF-8') . '...';
            }
            ?>
            <div class="post-card" data-post-id="<?= (int) $post['id'] ?>">
              <p class="text-date"><?= htmlspecialchars($post['created_at_formatted']) ?></p>
              <img src="<?= htmlspecialchars($post['img_url']) ?>" alt="Image">
              <h3><a href="/post?id=<?= (int) $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
              <p class="text-post"><?= htmlspecialchars($description) ?></p>
              <div class="text-author">
                <span>Текст</span>
                <p class="author-name"><?= htmlspecialchars($post['author']) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <?php if ($total_pages > 1): ?>
        <div class="slider-controls" data-total="<?= (int) $total_pages ?>">
          <button class="btn-control btn-prev" data-page="<?= $current_page - 1 ?>" <?= $current_page <= 1 ? 'disabled' : '' ?>>
          </button>

          <div class="page-index">
            <?php
            $start = max(1, $current_page - 2);
            $end = min($total_pages, $current_page + 2);
            for ($i = $start; $i <= $end; $i++):
              ?>
              <button class="page-num <?= $i == $current_page ? 'active' : '' ?>" data-page="<?= $i ?>">
                <?= $i ?>
              </button>
            <?php endfor; ?>
          </div>

          <button class="btn-control btn-next" data-page="<?= $current_page + 1 ?>" <?= $current_page >= $total_pages ? 'disabled' : '' ?>>
          </button>
        </div>
      <?php endif; ?>
    </div>
  </main>
  <script src="js/pagination.js"></script>
</body>

</html>