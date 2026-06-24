<?php
/**
 * Задание 2: Демонстрация работы с cookies
 */

$message = '';

// Установка cookie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_cookie'])) {
    $name = trim($_POST['cookie_name']);
    $value = trim($_POST['cookie_value']);
    $days = (int)($_POST['expire_days'] ?? 1);
    
    if (!empty($name) && !empty($value)) {
        setcookie($name, $value, time() + $days * 86400, '/');
        $message = "Cookie '$name' установлена";
    } else {
        $message = "Заполните оба поля";
    }
}

// Удаление конкретной cookie
if (isset($_POST['delete_cookie']) && isset($_POST['cookie_to_delete'])) {
    $name = $_POST['cookie_to_delete'];
    setcookie($name, '', time() - 3600, '/');
    $message = "Cookie '$name' удалена";
}

// Удаление всех cookie
if (isset($_POST['delete_all_cookies'])) {
    foreach ($_COOKIE as $name => $value) {
        setcookie($name, '', time() - 3600, '/');
    }
    $message = "Все cookie удалены";
    header("Refresh:0");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Задание 2 - Cookies</title>
</head>
<body>

    <h2>Работа с Cookies</h2>

    <?php if ($message): ?>
        <p><strong><?php echo $message; ?></strong></p>
    <?php endif; ?>

    <h3>Текущие cookies (<?php echo count($_COOKIE); ?> шт.):</h3>
    
    <?php if (empty($_COOKIE)): ?>
        <p>Нет установленных cookies</p>
    <?php else: ?>
        <ul>
        <?php foreach ($_COOKIE as $name => $value): ?>
            <li>
                <strong><?php echo htmlspecialchars($name); ?></strong> = <?php echo htmlspecialchars($value); ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="cookie_to_delete" value="<?php echo htmlspecialchars($name); ?>">
                    <button type="submit" name="delete_cookie">Удалить</button>
                </form>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <hr>

    <h3>Создать cookie:</h3>
    <form method="POST">
        <p>
            Имя: <input type="text" name="cookie_name" placeholder="user_name" required>
        </p>
        <p>
            Значение: <input type="text" name="cookie_value" placeholder="Иван" required>
        </p>
        <p>
            Срок: 
            <select name="expire_days">
                <option value="1">1 день</option>
                <option value="7">7 дней</option>
                <option value="30" selected>30 дней</option>
                <option value="365">1 год</option>
            </select>
        </p>
        <p>
            <button type="submit" name="set_cookie">Создать cookie</button>
            <button type="submit" name="delete_all_cookies" onclick="return confirm('Удалить все?')">Удалить все</button>
        </p>
    </form>

</body>
</html>