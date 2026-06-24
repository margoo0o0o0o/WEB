<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <!-- Минимальные стили для формы -->
    <style>
        body { font-family: Arial; margin: 50px; }
        form { width: 300px; }
        input, button { width: 100%; padding: 8px; margin: 5px 0; }
        button { background: blue; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Форма авторизации</h2>
    <!-- 
        Форма отправляет данные методом POST на файл auth.php
        action="auth.php" - указывает, куда отправлять данные
        method="POST" - данные передаются скрыто (не видны в URL)
    -->
    <form action="auth.php" method="POST">
        <label>Логин:</label>
        <!-- name="username" - это имя поля, по которому данные будут доступны в PHP -->
        <input type="text" name="username" required>
        
        <label>Пароль:</label>
        <!-- name="userpass" - имя поля для пароля -->
        <input type="password" name="userpass" required>
        
        <button type="submit">Войти</button>
    </form>
</body>
</html>