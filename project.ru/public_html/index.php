<?php
session_start();
define('PROJECT_ROOT', dirname(__DIR__, 2) . '/project-root');
require PROJECT_ROOT . '/config/db.php'; // создаёт $pdo

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$uri = strtok($uri, '?'); // Убираем GET-параметры
$uri = rtrim($uri, '/');  // Убираем завершающий слеш (/login/ → /login)
if ($uri === '')
  $uri = '/';

switch ($uri) {
  case '/':
    require PROJECT_ROOT . '/actions/blog.php';
    break;

  case '/post':
    require PROJECT_ROOT . '/actions/post.php';
    break;

  case '/log':
    require PROJECT_ROOT . '/actions/log.php';
    break;
  
  case '/logout':
    require PROJECT_ROOT . '/actions/logout.php';
    break;

  case '/reg':
    require PROJECT_ROOT . '/actions/reg.php';
    break;

  case '/admin_panel':
    require PROJECT_ROOT . '/actions/admin_panel.php';
    break;

  case '/add':
    require PROJECT_ROOT . '/actions/admin/add.php';
    break;

  case '/posts':
    require PROJECT_ROOT . '/actions/admin/posts.php';
    break;

  case '/edit':
    require PROJECT_ROOT . '/actions/admin/edit.php';
    break;
  
  case '/deletePost':
    require PROJECT_ROOT . '/actions/admin/deletePost.php';
    break;

  case '/deleteComment':
    require PROJECT_ROOT . '/actions/admin/deleteComment.php';
    break;

  case '/comments':
    require PROJECT_ROOT . '/actions/admin/comments.php';
    break;

  case '/add_comment':
    require PROJECT_ROOT . '/actions/add_comment.php';
    break;

  default:
    http_response_code(404);
    require PROJECT_ROOT . '/temps/404.php';
    exit;
}
?>