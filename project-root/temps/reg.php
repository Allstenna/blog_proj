<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title>Регистрация</title>
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
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
      <h1 class="">РЕГИСТРАЦИЯ</h1>
      <form method="POST" action="/reg">
        <a href="/" class="link-back"><b>⟵</b> Вернуться</a>
        <?php if (!empty($errorMsg)): ?>
          <div class="message-box">
            <?php echo $errorMsg; ?>
          </div>
        <?php endif; ?>
        <?php if (!empty($successMsg)): ?>
          <div class="message-box">
            <?php echo $successMsg; ?>
          </div>
        <?php endif; ?>
        <div class="input-field">
          <div class="form-input">
            <label class="form-label">Имя</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="form-input">
            <label class="form-label">Почта</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="form-input">
            <label class="form-label">Пароль</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="form-input">
            <label class="form-label">Подтверждение пароля</label>
            <input type="password" name="password_confirm" class="form-control" required>
          </div>
        </div>
        <button type="submit" class="btn">Регистрация</button>
        <a class="link-to" href="/log">Войти</a>
      </form>
    </div>
  </main>

</body>

</html>