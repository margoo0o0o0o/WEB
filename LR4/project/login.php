<?php
session_start();

// Если уже залогинен - пускаем в админку
if (isset($_SESSION['user'])) {
    header('Location: admin.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    // Читаем пользователей из JSON
    $users = json_decode(file_get_contents('users.json'), true);
    
    // Проверяем логин и пароль
    if (isset($users[$login]) && $password === $users[$login]['password']) {
        // Безопасность: пересоздаём ID сессии
        session_regenerate_id(true);
        
        // Сохраняем в сессию
        $_SESSION['user'] = $login;
        $_SESSION['role'] = $users[$login]['role'];
        $_SESSION['name'] = $users[$login]['name'];
        $_SESSION['logged_at'] = time();
        
        // "Запомнить меня" - сохраняем в cookie
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $_SESSION['remember_token'] = $token;
            setcookie('remember_token', $token, time() + 30 * 24 * 3600, '/');
        }
        
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Неверный логин или пароль';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
</head>
<body>
    <h2>Вход в админ-панель</h2>
    
    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <p>
            <label>Логин:</label><br>
            <input type="text" name="login" required>
        </p>
        <p>
            <label>Пароль:</label><br>
            <input type="password" name="password" required>
        </p>
        <p>
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Запомнить меня</label>
        </p>
        <button type="submit">Войти</button>
    </form>
    
    <hr>
    <p><strong>Тестовые данные:</strong></p>
    <p>Логин: admin | Пароль: admin123</p>
    <p>Логин: demo | Пароль: demo123</p>
</body>
</html>