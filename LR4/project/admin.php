<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['user'])) {
    // Проверяем cookie "запомнить меня"
    if (isset($_COOKIE['remember_token'])) {
        $_SESSION['user'] = 'admin';
        $_SESSION['role'] = 'admin';
        $_SESSION['name'] = 'Администратор';
        $_SESSION['restored'] = true;
    } else {
        header('Location: login.php');
        exit;
    }
}

// Таймаут 15 минут
$timeout = 900;
if (isset($_SESSION['logged_at']) && (time() - $_SESSION['logged_at']) > $timeout) {
    header('Location: logout.php?expired=1');
    exit;
}

// Обновляем время активности
$_SESSION['logged_at'] = time();

$isAdmin = ($_SESSION['role'] === 'admin');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
</head>
<body>
    <?php if (isset($_SESSION['restored']) && $_SESSION['restored']): ?>
        <p style="background: #ffc107; padding: 10px;">
            Сессия восстановлена через cookie "Запомнить меня"
        </p>
        <?php unset($_SESSION['restored']); ?>
    <?php endif; ?>
    
    <h1>Админ-панель</h1>
    
    <p>
        <a href="logout.php">Выйти</a>
    </p>
    
    <div style="background: #667eea; color: white; padding: 15px; margin: 20px 0;">
        <h2>Добро пожаловать, <?php echo $_SESSION['name']; ?>!</h2>
        <p><strong>Логин:</strong> <?php echo $_SESSION['user']; ?></p>
        <p><strong>Роль:</strong> <?php echo $isAdmin ? 'Администратор' : 'Пользователь'; ?></p>
        <p><strong>Время входа:</strong> <?php echo date('Y-m-d H:i:s', $_SESSION['logged_at']); ?></p>
    </div>
    
    <h3>Информация о сессии</h3>
    <ul>
        <li><strong>ID сессии:</strong> <?php echo session_id(); ?></li>
        <li><strong>Имя сессии:</strong> <?php echo session_name(); ?></li>
        <li><strong>Таймаут:</strong> 15 минут</li>
    </ul>
    
    <h3>Cookie</h3>
    <ul>
        <li><strong>Session cookie:</strong> <?php echo isset($_COOKIE[session_name()]) ? 'установлена' : 'не установлена'; ?></li>
        <li><strong>Remember token:</strong> <?php echo isset($_COOKIE['remember_token']) ? 'установлен' : 'не установлен'; ?></li>
    </ul>
    
    <?php if ($isAdmin): ?>
        <h3>Пользователи (только для админа)</h3>
        <table border="1" cellpadding="8">
            <tr>
                <th>Логин</th>
                <th>Роль</th>
                <th>Имя</th>
            </tr>
            <?php
            $users = json_decode(file_get_contents('users.json'), true);
            foreach ($users as $login => $data):
            ?>
            <tr>
                <td><?php echo $login; ?></td>
                <td><?php echo $data['role']; ?></td>
                <td><?php echo $data['name']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>