<?php
session_start();

// Очищаем сессию
$_SESSION = array();

// Удаляем cookie сессии
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

// Удаляем cookie "запомнить меня"
setcookie('remember_token', '', time() - 3600, '/');

// Уничтожаем сессию
session_destroy();

// Переходим на страницу входа
header('Location: login.php?logout=1');
exit;
?>