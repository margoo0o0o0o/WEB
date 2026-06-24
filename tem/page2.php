<?php
// === ЗАДАНИЕ №2 (Работа с сессиями) ===
// 1. Обязательно вызываем session_start(), чтобы получить доступ 
// к данным, сохраненным в сессии на предыдущем этапе.
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Страница 2</title>
</head>
<body>

    <?php
    // === ЗАДАНИЕ №2.2 (Вывод данных из сессии) ===
    // Проверяем, существуют ли наши переменные в сессии
    if (isset($_SESSION['name']) && isset($_SESSION['gender'])) {
        echo "<h2>Данные пользователя:</h2>";
        echo "Имя: " . htmlspecialchars($_SESSION['name']) . "<br>";
        echo "Пол: " . htmlspecialchars($_SESSION['gender']) . "<br>";
    } else {
        echo "<p>Данные в сессии не найдены. Пожалуйста, заполните форму на первой странице.</p>";
    }
    
    // === ЗАДАНИЕ №2.3 (Вывод ID сессии) ===
    // Функция session_id() возвращает уникальный идентификатор текущей сессии
    echo "<br><strong>Идентификатор СЕССИИ:</strong> " . session_id();
    ?>

    <br><br>
    <a href="index.html">Вернуться к форме</a>

</body>
</html>