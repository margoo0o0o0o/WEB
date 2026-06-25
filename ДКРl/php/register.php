<?php
// Подключение к БД
require_once 'config.php'; // В config.php уже есть данные для подключения, мы их используем
// Используем переменные из config.php для подключения
$conn = mysqli_connect('127.0.0.1', 'root', '05rn05', 'study_moov');
if (!$conn) {
    die('Ошибка подключения: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    $pass_confirm = $_POST['password_confirm'] ?? '';

    // Проверки
    if (empty($username) || empty($email) || empty($pass) || empty($pass_confirm)) {
        $error = 'Заполните все поля!';
    } elseif ($pass !== $pass_confirm) {
        $error = 'Пароли не совпадают!';
    } elseif (strlen($pass) < 6) {
        $error = 'Пароль должен быть не менее 6 символов!';
    } else {
        // Проверка на существование пользователя
        $check = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username' OR email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = 'Пользователь с таким логином или email уже существует!';
        } else {
            // Хешируем пароль
            $hashed = password_hash($pass, PASSWORD_DEFAULT);
            
            // Вставляем в БД
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed')";
            if (mysqli_query($conn, $sql)) {
                $success = 'Регистрация успешна! Теперь можете войти.';
            } else {
                $error = 'Ошибка при регистрации: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <style>
        body { font-family: Arial; max-width: 400px; margin: 50px auto; padding: 20px; background: #f5f7fa; }
        .box { background: white; padding: 30px; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        h2 { color: #1e5eff; }
        input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin: 5px 0 15px 0; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #1e5eff; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; }
        button:hover { background: #0a4ae6; }
        .error { color: red; }
        .success { color: green; }
        .link { text-align: center; margin-top: 15px; }
        .link a { color: #1e5eff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="box">
        <h2>📝 Регистрация</h2>
        
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль (мин. 6 символов)" required>
            <input type="password" name="password_confirm" placeholder="Подтвердите пароль" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
        
        <div class="link">
            Уже есть аккаунт? <a href="login.php">Войти</a>
        </div>
    </div>
</body>
</html>