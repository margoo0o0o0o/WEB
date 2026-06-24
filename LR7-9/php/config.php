<?php
// ============================================
// КОНФИГУРАЦИОННЫЙ ФАЙЛ ДЛЯ 6-Й РАБОТЫ
// ============================================

// НАСТРОЙКИ САЙТА
define('SITE_NAME', 'Study Moov');
define('ADMIN_EMAIL', 'pats.m@bk.ru');   // СЮДА ПРИДУТ ПИСЬМА

// НАСТРОЙКИ SMTP ДЛЯ MAIL.RU
define('SMTP_HOST', 'smtp.mail.ru');     // ← ПОМЕНЯЛА НА MAIL.RU!
define('SMTP_PORT', 465);
define('SMTP_USER', 'pats.m@bk.ru');
define('SMTP_PASS', 'xVaJ52bgpIC2gk6aPsgB'); // ← ОБЫЧНЫЙ ПАРОЛЬ!
define('SMTP_SECURE', 'ssl');

// СОЗДАЁМ НУЖНЫЕ ПАПКИ
foreach (['data', 'logs'] as $dir) {
    if (!file_exists(__DIR__ . '/' . $dir)) {
        mkdir(__DIR__ . '/' . $dir, 0777, true);
    }
}
?>