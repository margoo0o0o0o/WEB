<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Выход
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 50px auto; padding: 20px; background: #f5f7fa; }
        .box { background: white; padding: 30px; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        h2 { color: #1e5eff; }
        .info { background: #f0f4ff; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .logout { background: #dc3545; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-block; }
        .logout:hover { background: #c82333; }
        .back-link { color: #1e5eff; text-decoration: none; display: inline-block; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="box">
        <h2>👤 Профиль</h2>
        <p>Добро пожаловать, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>
        <div class="info">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <p><strong>Роль:</strong> <?php echo htmlspecialchars($_SESSION['role']); ?></p>
            <p><strong>Время входа:</strong> <?php echo date('d.m.Y H:i:s', $_SESSION['logged_at']); ?></p>
        </div>

        <p>
            <a href="?logout=1" class="logout">Выйти</a>
        </p>
        <p><a href="../index.html" class="back-link">← На главную</a></p>
    </div>
</body>
</html>