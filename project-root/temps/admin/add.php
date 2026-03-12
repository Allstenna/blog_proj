<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title>Добавление поста</title>
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
      <h1 class="">Добавить пост</h1>
      <form method="POST" enctype="multipart/form-data">
        <a href="/posts" class="link-back"><b>⟵</b> Вернуться</a>
        <?php if (!empty($message)): ?>
            <div class="message-box">
              <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <div class="input-field">
          <div class="form-input">
            <label class="form-label">Название</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="form-input">
            <label class="form-label">Автор</label>
            <input type="text" name="author" class="form-control" required>
          </div>
          <div class="form-input">
            <label class="form-label">Картинка</label>
            <div class="form-control"><input type="file" name="img"></div>
          </div>
          <div class="form-input">
            <label class="form-label">Описание</label>
            <textarea type="text" name="description" class="form-control" required></textarea>
          </div>
        </div>
        <button type="submit" class="btn">Добавить</button>
      </form>
    </div>
  </main>

</body>
<script src="js/script.js"></script>

</html>