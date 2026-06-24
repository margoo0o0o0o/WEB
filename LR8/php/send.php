<?php
// ============================================
// ЗАДАНИЕ 6: ОТПРАВКА ПИСЕМ
// ============================================

require_once 'config.php';
require_once '../PHPMailer-master/src/Exception.php';
require_once '../PHPMailer-master/src/PHPMailer.php';
require_once '../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Получаем данные из формы
$name = $_POST['user_name'] ?? 'Аноним';
$email = $_POST['user_email'] ?? '';
$phone = $_POST['user_phone'] ?? '';
$message = $_POST['user_message'] ?? '';

// Формируем письмо
$subject = "Заявка на обучение от $name";
$email_body = "
    <h2>Новая заявка с сайта Study Moov</h2>
    <p><strong>Имя:</strong> $name</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Телефон:</strong> $phone</p>
    <p><strong>Вопрос:</strong> $message</p>
    <hr>
    <p style='color: #999; font-size: 12px;'>Отправлено через форму на сайте</p>
";

// ============================================
// ЗАДАНИЕ 1: ОТПРАВКА ЧЕРЕЗ mail()
// ============================================
//$headers = "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: $email\r\n";
//mail(ADMIN_EMAIL, $subject, $email_body, $headers);

// ============================================
// ЗАДАНИЕ 2: ОТПРАВКА ЧЕРЕЗ PHPMailer
// ============================================
$mail = new PHPMailer(true);
$result = ['success' => false, 'message' => ''];

try {
    // Настройки SMTP
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port = SMTP_PORT;
    $mail->CharSet = 'UTF-8';

    // Отправитель
    $mail->setFrom(SMTP_USER, SITE_NAME);
    $mail->addReplyTo($email, $name);

    // ЗАДАНИЕ 2.3: НЕСКОЛЬКО АДРЕСАТОВ
    $mail->addAddress(ADMIN_EMAIL);
    // $mail->addAddress('manager@study-moov.ru'); // раскомментируй если нужно

    // ЗАДАНИЕ 2.2: ВЛОЖЕНИЕ
    if (isset($_FILES['user_file']) && $_FILES['user_file']['error'] === UPLOAD_ERR_OK) {
        $mail->addAttachment($_FILES['user_file']['tmp_name'], $_FILES['user_file']['name']);
    }

    // Содержимое письма
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $email_body;

    $mail->send();
    $result['success'] = true;
    $result['message'] = 'Письмо отправлено!';

} catch (Exception $e) {
    $result['message'] = "Ошибка: {$mail->ErrorInfo}";
}

// ============================================
// СОХРАНЕНИЕ ЗАЯВКИ В ФАЙЛ
// ============================================
$dataFile = __DIR__ . '/data/requests.txt';
$record = date('Y-m-d H:i:s') . " | $name | $email | $phone | $message\n";
file_put_contents($dataFile, $record, FILE_APPEND);

// ============================================
// ОТВЕТ ПОЛЬЗОВАТЕЛЮ
// ============================================
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заявка отправлена</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #f5f7fa;
        }
        .container {
            text-align: center;
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            max-width: 500px;
        }
        .success { color: #28a745; font-size: 48px; margin-bottom: 20px; }
        .error { color: #dc3545; font-size: 48px; margin-bottom: 20px; }
        h1 { color: #333; }
        p { color: #666; line-height: 1.6; }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: #1e5eff;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
        }
        .btn:hover { background: #0a4ae6; }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($result['success']): ?>
            <div class="success">✅</div>
            <h1>Заявка отправлена!</h1>
            <p>Наш консультант свяжется с вами в ближайшее время.</p>
        <?php else: ?>
            <div class="error">❌</div>
            <h1>Ошибка отправки</h1>
            <p><?php echo htmlspecialchars($result['message']); ?></p>
        <?php endif; ?>
        <a href="../index.html" class="btn">← На главную</a>
    </div>
</body>
</html>