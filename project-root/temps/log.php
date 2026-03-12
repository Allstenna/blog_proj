<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title>Авторизация</title>
  <link rel="icon" type="image/png" href="img/favicon.png">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <header>
    <nav>
      <a href="/" class="logo-link">
        <img src="img/logo.svg" alt="FYRRE BLOG" class="logo">
      </a>
    </nav>
  </header>
  <main>
    <div class="content">
      <h1 class="">ВОЙТИ</h1>
      <form method="POST" action="/log">
        <a href="/" class="link-back"><b>⟵</b> Вернуться</a>
        <?php if (!empty($errorMsg)): ?>
            <div class="message-box">
              <?php echo $errorMsg; ?>
            </div>
        <?php endif; ?>
        <div class="input-field">
          <div class="form-input">
            <label class="form-label">Почта</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="form-input">
            <label class="form-label">Пароль</label>
            <input type="password" name="password" class="form-control" required>
          </div>
        </div>
        <button type="submit" class="btn">Войти</button>
        <a class="link-to" href="/reg">Регистрация</a>
      </form>
    </div>
  </main>

</body>
<script src="js/script.js"></script>

</html>