<?php
/**
 * ЗАДАНИЕ №2: Просмотр вопросов-ответов
 * Цель: Показать все сохранённые вопросы и ответы из файла
 */

// 1. ЧТЕНИЕ ФАЙЛА С ВОПРОСАМИ

// __DIR__ - путь к текущей папке (php/)
// /data/faq.txt - добавляем путь к файлу с вопросами
$filename = __DIR__ . '/data/faq.txt';

// file_exists() - проверяет, существует ли файл
// ? : - тернарный оператор (если да - то одно, если нет - другое)
// file_get_contents() - читает ВЕСЬ файл целиком в переменную
// Если файла нет - выводим сообщение "Вопросов пока нет"
$content = file_exists($filename) ? file_get_contents($filename) : 'Вопросов пока нет';
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вопрос-ответ</title>
    
    <!-- ===== СТИЛИ ДЛЯ СТРАНИЦЫ ===== -->
    <style>
        /* Общие стили */
        body { 
            font-family: Arial; 
            max-width: 900px; 
            margin: 40px auto; 
            padding: 0 20px; 
            background: #f5f7fa; 
        }
        h1 { 
            border-left: 5px solid #1e5eff; 
            padding-left: 20px; 
        }
        
        /* Один вопрос-ответ (карточка) */
        .faq-item { 
            background: white; 
            padding: 20px; 
            border-radius: 12px; 
            margin-bottom: 15px; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.05); 
        }
        .question { 
            font-weight: 700; 
            color: #1e5eff; 
        }
        .answer { 
            color: #333; 
            margin-top: 10px; 
        }
        .date { 
            color: #999; 
            font-size: 12px; 
            margin-top: 10px; 
        }
        
        /* Ссылка назад */
        .back-link { 
            display: inline-block; 
            margin-top: 20px; 
            color: #1e5eff; 
            text-decoration: none; 
            font-weight: 600; 
        }
        
        /* Форма добавления */
        .form-box { 
            background: white; 
            padding: 25px; 
            border-radius: 12px; 
            margin-bottom: 30px; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.05); 
        }
        input, textarea { 
            padding: 10px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            width: 100%; 
            max-width: 400px; 
        }
        button { 
            background: #1e5eff; 
            color: white; 
            border: none; 
            padding: 10px 24px; 
            border-radius: 40px; 
            cursor: pointer; 
        }
        button:hover { 
            background: #0a4ae6; 
        }
        
        /* Сообщения об успехе/ошибке */
        .msg { 
            padding: 10px; 
            border-radius: 8px; 
            margin-bottom: 15px; 
        }
        .msg-success { 
            background: #d4edda; 
            color: #155724; 
        }
        .msg-error { 
            background: #f8d7da; 
            color: #721c24; 
        }
    </style>
</head>
<body>
    
    <!-- ===== ЗАГОЛОВОК СТРАНИЦЫ ===== -->
    <h1>Вопрос-ответ</h1>
    
    
    <!-- БЛОК 1: ФОРМА ДОБАВЛЕНИЯ НОВОГО ВОПРОСА -->
    <div class="form-box">
        
        <h3>Добавить вопрос-ответ</h3>
        
        <!-- Сюда будет выводиться сообщение об успехе/ошибке -->
        <div id="formMessage"></div>
        
        <!-- 
            Форма отправляется через JavaScript (fetch)
            action не указываем, так как отправка идёт через JS
        -->
        <form id="faqForm">
            <p>
                <!-- Поле для ввода вопроса -->
                <input type="text" id="question" placeholder="Вопрос" required>
            </p>
            <p>
                <!-- Поле для ввода ответа -->
                <textarea id="answer" rows="3" style="width:100%; max-width:400px;" placeholder="Ответ" required></textarea>
            </p>
            <!-- Кнопка отправки -->
            <button type="submit">Добавить</button>
        </form>
    </div>
    
    
    <!-- БЛОК 2: СПИСОК ВСЕХ СОХРАНЁННЫХ ВОПРОСОВ -->
    <h2>Все вопросы:</h2>
    
    <?php
    // Проверяем, существует ли файл с вопросами
    if (file_exists($filename)) {
        
        // explode() - разбивает строку на массив по разделителю
        // Разделитель: "\n---\n" - это разделитель между вопросами
        // Получаем массив всех вопросов
        $entries = explode("\n---\n", $content);
        
        // Перебираем каждый вопрос
        foreach ($entries as $entry) {
            
            // trim() - убираем лишние пробелы
            // Если запись пустая - пропускаем её
            if (trim($entry) == '') continue;
            
            // explode() - разбиваем запись по переносам строк
            // Получаем массив: [0] - вопрос, [1] - ответ, [2] - дата
            $lines = explode("\n", $entry);
            
            // str_replace() - убираем текст "Вопрос: " из строки
            // ?? '' - если строки нет, то пустая строка
            $question = str_replace('Вопрос: ', '', $lines[0] ?? '');
            $answer = str_replace('Ответ: ', '', $lines[1] ?? '');
            $date = str_replace('Дата: ', '', $lines[2] ?? '');
            
            // Выводим карточку с вопросом и ответом
            ?>
            <div class="faq-item">
                <!-- Вопрос -->
                <div class="question">❓ <?php echo htmlspecialchars($question); ?></div>
                
                <!-- Ответ (nl2br - заменяет \n на <br>) -->
                <div class="answer">✅ <?php echo nl2br(htmlspecialchars($answer)); ?></div>
                
                <!-- Дата добавления -->
                <div class="date">📅 <?php echo htmlspecialchars($date); ?></div>
            </div>
            <?php
        }
        
    } else {
        // Если файла нет - выводим сообщение
        echo '<p>Вопросов пока нет</p>';
    }
    ?>
    
    
    <!-- ===== ССЫЛКА НА ГЛАВНУЮ СТРАНИЦУ ===== -->
    <a href="../index.html" class="back-link">← На главную</a>


    <!-- БЛОК 3: JAVASCRIPT ДЛЯ ОТПРАВКИ ФОРМЫ -->
    <script>
        // document.getElementById() - находим форму по ID
        document.getElementById('faqForm').addEventListener('submit', function(e) {
            
            // e.preventDefault() - отменяет стандартную отправку формы
            // (чтобы страница не перезагружалась)
            e.preventDefault();
            
            // Получаем значения полей
            const question = document.getElementById('question').value;
            const answer = document.getElementById('answer').value;
            
            // Находим блок для вывода сообщения
            const msg = document.getElementById('formMessage');
            
            
            // ОТПРАВКА ДАННЫХ НА СЕРВЕР (AJAX)
            
            // fetch() - отправляет запрос на сервер
            // 'save_faq.php' - адрес, куда отправляем
            fetch('save_faq.php', {
                
                method: 'POST', // Метод POST
                
                // Заголовки: говорим, что отправляем данные формы
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                
                // Тело запроса: question=текст&answer=текст
                // encodeURIComponent() - кодирует спецсимволы
                body: 'question=' + encodeURIComponent(question) + '&answer=' + encodeURIComponent(answer)
            })
            
            // .then() - когда пришёл ответ от сервера
            // response.json() - превращает ответ в объект
            .then(response => response.json())
            
            // Обрабатываем полученные данные
            .then(data => {
                
                if (data.success) {
                    // Если всё успешно - показываем зелёное сообщение
                    msg.innerHTML = '<div class="msg msg-success">' + data.message + '</div>';
                    
                    // Очищаем поля формы
                    document.getElementById('question').value = '';
                    document.getElementById('answer').value = '';
                    
                    // Через 1 секунду перезагружаем страницу
                    // (чтобы показать новый вопрос в списке)
                    setTimeout(() => location.reload(), 1000);
                    
                } else {
                    // Если ошибка - показываем красное сообщение
                    msg.innerHTML = '<div class="msg msg-error">' + data.message + '</div>';
                }
            })
            
            // .catch() - если произошла ошибка при отправке
            .catch(() => {
                msg.innerHTML = '<div class="msg msg-error">Ошибка сервера</div>';
            });
        });
    </script>
    
</body>
</html>