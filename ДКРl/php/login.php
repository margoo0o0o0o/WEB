<?php
session_start();

// Подключение к БД
$conn = mysqli_connect('127.0.0.1', 'root', '05rn05', 'study_moov');
if (!$conn) {
    die('Ошибка подключения: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8');

// Если уже залогинен — на страницу профиля
if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    if (empty($username) || empty($pass)) {
        $error = 'Введите логин и пароль!';
    } else {
        $sql = "SELECT id, username, email, password, role FROM users WHERE username = '$username' OR email = '$username'";
        $result = mysqli_query($conn, $sql);
        $user_data = mysqli_fetch_assoc($result);

        if ($user_data && password_verify($pass, $user_data['password'])) {
            // Вход успешен — создаём сессию
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['email'] = $user_data['email'];
            $_SESSION['role'] = $user_data['role'];
            $_SESSION['logged_at'] = time();

            header('Location: profile.php');
            exit;
        } else {
            $error = 'Неверный логин или пароль!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <style>
        body { font-family: Arial; max-width: 400px; margin: 50px auto; padding: 20px; background: #f5f7fa; }
        .box { background: white; padding: 30px; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        h2 { color: #1e5eff; }
        input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin: 5px 0 15px 0; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #1e5eff; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; }
        button:hover { background: #0a4ae6; }
        .error { color: red; }
        .link { text-align: center; margin-top: 15px; }
        .link a { color: #1e5eff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="box">
        <h2>🔐 Вход</h2>

        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Логин или Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>

        <div class="link">
            Нет аккаунта? <a href="register.php">Зарегистрироваться</a>
        </div>
    </div>
</body>
</html>