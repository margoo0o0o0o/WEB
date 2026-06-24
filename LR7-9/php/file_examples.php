<?php
/**
 * ЗАДАНИЕ №1: Типовые действия с файлами
 * Цель: Показать основные функции работы с файлами в PHP
 */

// 1. СОЗДАНИЕ ПАПКИ ДЛЯ ФАЙЛОВ

// __DIR__ - это путь к папке, где лежит текущий скрипт (php/)
// Добавляем к нему /data - получаем путь: php/data
$dataDir = __DIR__ . '/data';

// is_dir() - проверяет, существует ли папка
// Если папки нет - создаём её
if (!is_dir($dataDir)) {
    // mkdir() - создаёт папку
    // 0777 - права доступа (читать и писать могут все)
    // true - создавать вложенные папки, если их нет
    mkdir($dataDir, 0777, true);
}

// Полный путь к файлу, с которым будем работать
// Например: php/data/example.txt
$demoFile = $dataDir . '/example.txt';

// 2. ФУНКЦИЯ ДЛЯ ЧТЕНИЯ ФАЙЛА

/**
 * Читает содержимое файла и возвращает его в виде HTML
 * 
 * @param string $filename - путь к файлу (например: "data/example.txt")
 * @return string - содержимое файла с переносами строк
 */
function readFileContent($filename) {
    
    // Переменная, куда будем собирать прочитанный текст
    $result = '';
    
    // file_exists() - проверяет, существует ли файл на диске
    if (file_exists($filename)) {
        
        // fopen() - открывает файл
        // "r" - режим "только чтение" (read)
        // Если файла нет - fopen() вернёт false
        $f = fopen($filename, 'r');
        
        // Проверяем, что файл открылся успешно
        if ($f) {
            
            // feof() - проверяет, достигнут ли конец файла
            // (end of file)
            // Пока не достигнут конец - читаем строки
            while(!feof($f)) {
                
                // fgets() - читает одну строку из файла
                // (до символа переноса строки \n)
                $line = fgets($f);
                
                // trim() - убирает лишние пробелы в начале и конце строки
                // Если строка не пустая - добавляем её в результат
                if (trim($line) !== '') {
                    
                    // htmlspecialchars() - превращает HTML-символы в текст
                    // Например: <b>текст</b> → &lt;b&gt;текст&lt;/b&gt;
                    // Это защита от XSS-атак (чтобы код не выполнялся)
                    // <br> - это HTML-тег переноса строки
                    $result .= htmlspecialchars($line) . "<br>";
                }
            }
            
            // fclose() - ОБЯЗАТЕЛЬНО закрываем файл!
            // Иначе файл останется заблокированным
            fclose($f);
        }
    } else {
        // Если файл не найден - выводим сообщение
        $result = "Файл не найден";
    }
    
    // Возвращаем прочитанный текст
    return $result;
}


// 3. ФУНКЦИЯ ДЛЯ ЗАПИСИ В ФАЙЛ

/**
 * Записывает текст в файл (режим "w" - перезапись)
 * 
 * @param string $filename - путь к файлу
 * @param string $content - текст для записи
 * @return bool - true если запись успешна, false если ошибка
 */
function writeToFile($filename, $content) {
    
    // fopen() - открывает файл
    // "w" - режим "запись" (write)
    // Если файла нет - создаётся новый
    // Если файл есть - старый содержимое стирается (перезапись)
    $f = fopen($filename, 'w');
    
    // Проверяем, что файл открылся
    if ($f) {
        
        // fwrite() - записывает текст в файл
        // . "\n" - добавляем символ переноса строки в конце
        fwrite($f, $content . "\n");
        
        // fclose() - закрываем файл
        fclose($f);
        
        // Возвращаем true - запись прошла успешно
        return true;
    }
    
    // Если не удалось открыть файл - возвращаем false
    return false;
}


// 4. СОЗДАНИЕ ПРИМЕРНОГО ФАЙЛА (при первом запуске)

// Если файл ещё не существует
if (!file_exists($demoFile)) {
    
    // Создаём файл с примерным текстом
    // \n - это символ переноса строки
    writeToFile($demoFile, "PHP is fun!\nЭто строка 2\nСтрока 3 - Hello!");
}


// 5. ОБРАБОТКА ФОРМЫ (когда пользователь нажал "Записать")

// Переменная для сообщения пользователю (успех/ошибка)
$message = '';

// $_SERVER['REQUEST_METHOD'] - определяет метод запроса
// 'POST' - значит данные отправлены через форму
// isset($_POST['write']) - проверяет, была ли нажата кнопка "Записать"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['write'])) {
    
    // $_POST['text'] - получаем текст из поля textarea
    // ?? '' - если поле пустое, то используем пустую строку
    $text = $_POST['text'] ?? '';
    
    // Проверяем, что пользователь ввёл не пустой текст
    if (!empty($text)) {
        
        // Записываем текст в файл (перезаписываем)
        // writeToFile() - наша функция
        if (writeToFile($demoFile, $text)) {
            $message = 'Файл перезаписан!';
        }
    }
}


// 6. ЧТЕНИЕ ФАЙЛА ДЛЯ ОТОБРАЖЕНИЯ НА СТРАНИЦЕ

// Читаем содержимое файла через нашу функцию
// readFileContent() - возвращает HTML с текстом файла
$fileContent = readFileContent($demoFile);
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ЗАДАНИЕ №1 - Работа с файлами</title>
    <style>
        /* ===== ОБЩИЕ СТИЛИ СТРАНИЦЫ ===== */
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
        
        /* ===== БЛОКИ С ПРИМЕРАМИ ===== */
        .box { 
            background: white; 
            border-radius: 16px; 
            padding: 25px; 
            margin-bottom: 30px; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.05); 
        }
        
        /* ===== КОД ПРИМЕРОВ (тёмный фон) ===== */
        pre { 
            background: #2d2f36; 
            color: #e0e3e8; 
            padding: 20px; 
            border-radius: 12px; 
            overflow-x: auto; 
        }
        
        /* ===== СОДЕРЖИМОЕ ФАЙЛА ===== */
        .file-content { 
            background: #f0f2f5; 
            padding: 20px; 
            border-radius: 12px; 
            font-family: monospace; 
        }
        
        /* ===== ПОЛЯ ВВОДА ===== */
        input, textarea { 
            padding: 10px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            width: 100%; 
            max-width: 400px; 
        }
        
        /* ===== КНОПКИ ===== */
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
        
        /* ===== ССЫЛКА НА ГЛАВНУЮ ===== */
        .back-link { 
            display: inline-block; 
            margin-top: 20px; 
            color: #1e5eff; 
            text-decoration: none; 
            font-weight: 600; 
        }
        
        /* ===== ТАБЛИЦА С ФУНКЦИЯМИ ===== */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: left; 
        }
        th { 
            background: #f0f2f5; 
        }
    </style>
</head>
<body>
    
    <!-- ===== ЗАГОЛОВОК СТРАНИЦЫ ===== -->
    <h1>ЗАДАНИЕ №1: Работа с файлами</h1>
    

    <!-- БЛОК 1: ЧТЕНИЕ ФАЙЛА (ПРИМЕР 3.1 И 3.2 ИЗ ТЕОРИИ) -->
    <div class="box">
        
        <h2>Чтение файла (fopen + fgets)</h2>
        
        <!-- Выводим содержимое файла, которое прочитали через PHP -->
        <div class="file-content"><?php echo $fileContent; ?></div>
        
        <!-- Показываем код, который это делает -->
        <h3>Как это работает (код):</h3>
        <pre>
&lt;?php
// 1. Открываем файл в режиме "r" (только чтение)
$f = fopen("example.txt", "r");

// 2. Читаем построчно, пока не дойдём до конца файла
while(!feof($f)) {
    // fgets() - читает одну строку
    echo fgets($f);
}

// 3. Закрываем файл (ОБЯЗАТЕЛЬНО!)
fclose($f);
?&gt;
        </pre>
        
        <p>
            <strong>Объяснение:</strong> 
            Файл открывается функцией <code>fopen()</code> в режиме чтения <code>"r"</code>.
            Затем в цикле <code>while</code> построчно читается функцией <code>fgets()</code>,
            пока <code>feof()</code> не покажет, что достигнут конец файла.
            В конце файл обязательно закрывается <code>fclose()</code>.
        </p>
    </div>
    
    
    <!-- БЛОК 2: ЗАПИСЬ В ФАЙЛ (ПРИМЕР 4.1 ИЗ ТЕОРИИ) -->
    <div class="box">
        
        <h2>Запись в файл (fopen + fwrite)</h2>
        
        <!-- Сообщение об успехе или ошибке -->
        <?php if ($message): ?>
            <p style="color: green;"><?php echo $message; ?></p>
        <?php endif; ?>
        
        <!-- Форма: пользователь вводит текст и нажимает "Записать" -->
        <form method="POST">
            <textarea name="text" rows="3" style="width:100%; max-width:400px;" placeholder="Введите текст для записи"></textarea><br>
            <button type="submit" name="write">Записать</button>
        </form>
        
        <!-- Показываем код, который это делает -->
        <h3>Как это работает (код):</h3>
        <pre>
&lt;?php
// 1. Открываем файл в режиме "w" (запись, перезапись)
$f = fopen("example.txt", "w");

// 2. Записываем текст в файл
fwrite($f, "Текст для записи");

// 3. Закрываем файл (ОБЯЗАТЕЛЬНО!)
fclose($f);
?&gt;
        </pre>
        
        <p>
            <strong>Объяснение:</strong> 
            Файл открывается функцией <code>fopen()</code> в режиме записи <code>"w"</code>.
            Этот режим <strong>перезаписывает</strong> файл (старое содержимое удаляется).
            Данные записываются функцией <code>fwrite()</code>.
            В конце файл обязательно закрывается <code>fclose()</code>.
        </p>
        
        <p>
            <strong>Для добавления</strong> в конец файла используется режим <code>"a"</code> (append).
            Тогда новое содержимое дописывается в конец, а старое не удаляется.
        </p>
    </div>
    
    
    <!-- БЛОК 3: СПИСОК ВСЕХ ОПЕРАЦИЙ С ФАЙЛАМИ -->
    <div class="box">
        
        <h2>Типовые операции с файлами</h2>
        
        <table>
            <tr>
                <th>Операция</th>
                <th>Функция PHP</th>
            </tr>
            <tr>
                <td>Открытие файла</td>
                <td><code>fopen()</code></td>
            </tr>
            <tr>
                <td>Чтение (построчно)</td>
                <td><code>fgets()</code></td>
            </tr>
            <tr>
                <td>Чтение (целиком)</td>
                <td><code>fread()</code> или <code>file_get_contents()</code></td>
            </tr>
            <tr>
                <td>Запись в файл</td>
                <td><code>fwrite()</code></td>
            </tr>
            <tr>
                <td>Закрытие файла</td>
                <td><code>fclose()</code></td>
            </tr>
            <tr>
                <td>Проверка существования файла</td>
                <td><code>file_exists()</code></td>
            </tr>
            <tr>
                <td>Проверка конца файла</td>
                <td><code>feof()</code></td>
            </tr>
            <tr>
                <td>Удаление файла</td>
                <td><code>unlink()</code></td>
            </tr>
            <tr>
                <td>Размер файла</td>
                <td><code>filesize()</code></td>
            </tr>
            <tr>
                <td>Время последнего изменения</td>
                <td><code>filemtime()</code></td>
            </tr>
        </table>
        
        <p style="margin-top: 15px;">
            <strong>Режимы открытия файла:</strong>
            <code>"r"</code> — чтение, 
            <code>"w"</code> — запись (перезапись), 
            <code>"a"</code> — добавление в конец.
        </p>
    </div>
    
    
    <!-- ===== ССЫЛКА НА ГЛАВНУЮ СТРАНИЦУ САЙТА ===== -->
    <a href="../index.html" class="back-link">← На главную</a>
    
</body>
</html>