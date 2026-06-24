<?php
// ============================================
// ПРОСМОТР ЗАЯВОК (ДЛЯ АДМИНИСТРАТОРА)
// ============================================

$filename = __DIR__ . '/data/requests.txt';
$content = file_exists($filename) ? file_get_contents($filename) : 'Заявок пока нет';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заявки | Study Moov</title>
    <style>
        body { font-family: Arial; max-width: 1000px; margin: 40px auto; padding: 0 20px; background: #f5f7fa; }
        h1 { border-left: 5px solid #1e5eff; padding-left: 20px; }
        .box { background: white; border-radius: 16px; padding: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        pre { background: #1e1e1e; color: #d4d4d4; padding: 20px; border-radius: 12px; overflow-x: auto; font-size: 14px; }
        .back-link { display: inline-block; margin-top: 20px; color: #1e5eff; text-decoration: none; font-weight: 600; }
        .stats { margin-bottom: 20px; color: #666; }
    </style>
</head>
<body>
    <h1>📋 Заявки на обучение</h1>
    <div class="box">
        <?php
        $lines = file_exists($filename) ? file($filename) : [];
        $count = count($lines);
        ?>
        <div class="stats">Всего заявок: <strong><?php echo $count; ?></strong></div>
        <pre><?php echo htmlspecialchars($content); ?></pre>
    </div>
    <a href="../index.html" class="back-link">← На главную</a>
</body>
</html>