<?php
/**
 * КОРЗИНА С СОХРАНЕНИЕМ В БД
 */

session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Подключение к БД
$host = '127.0.0.1';
$user = 'root';
$password = '05rn05';
$dbname = 'study_moov';

$conn = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
    die('Ошибка подключения: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8');

// ============================================================
// ОБРАБОТКА ДЕЙСТВИЙ
// ============================================================

// ДОБАВЛЕНИЕ В КОРЗИНУ
if (isset($_GET['add'])) {
    $university_id = (int)$_GET['add'];
    
    $check = mysqli_query($conn, "SELECT id, quantity FROM cart WHERE user_id = $user_id AND university_id = $university_id");
    
    if (mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);
        $new_qty = $row['quantity'] + 1;
        mysqli_query($conn, "UPDATE cart SET quantity = $new_qty WHERE user_id = $user_id AND university_id = $university_id");
    } else {
        mysqli_query($conn, "INSERT INTO cart (user_id, university_id, quantity) VALUES ($user_id, $university_id, 1)");
    }
    
    header('Location: cart_db.php');
    exit;
}

// УДАЛЕНИЕ
if (isset($_GET['remove'])) {
    $university_id = (int)$_GET['remove'];
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id AND university_id = $university_id");
    header('Location: cart_db.php');
    exit;
}

// ОЧИСТКА
if (isset($_GET['clear'])) {
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");
    header('Location: cart_db.php');
    exit;
}

// УМЕНЬШЕНИЕ КОЛИЧЕСТВА
if (isset($_GET['decrease'])) {
    $university_id = (int)$_GET['decrease'];
    $result = mysqli_query($conn, "SELECT quantity FROM cart WHERE user_id = $user_id AND university_id = $university_id");
    $row = mysqli_fetch_assoc($result);
    
    if ($row['quantity'] > 1) {
        $new_qty = $row['quantity'] - 1;
        mysqli_query($conn, "UPDATE cart SET quantity = $new_qty WHERE user_id = $user_id AND university_id = $university_id");
    } else {
        mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id AND university_id = $university_id");
    }
    
    header('Location: cart_db.php');
    exit;
}

// ============================================================
// ПОЛУЧАЕМ ТОВАРЫ В КОРЗИНЕ
// ============================================================

$query = "
    SELECT c.id as cart_id, c.quantity, u.id, u.name, u.country, u.price, u.rating
    FROM cart c
    JOIN universities u ON c.university_id = u.id
    WHERE c.user_id = $user_id
";
$result = mysqli_query($conn, $query);
$cart_items = [];
$total = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
    $cart_items[] = $row;
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        /* ===== ОБЩИЕ СТИЛИ ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #f5f7fa;
            padding: 40px 20px;
            font-family: 'Inter', sans-serif;
            color: #000000 !important;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.05);
        }

        /* ===== ВСЕ ТЕКСТЫ — ЧЁРНЫЕ! ===== */
        h1, h2, h3, h4, p, span, th, td, li, div {
            color: #000000 !important;
        }

        .user-info {
            background: #e8f0fe;
            padding: 10px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            color: #000000 !important;
        }
        .user-info .name {
            font-weight: 600;
            color: #1e5eff !important;
        }
        .user-info a {
            color: #dc3545 !important;
            text-decoration: none;
            font-weight: 600;
        }

        h1 {
            border-left: 5px solid #1e5eff;
            padding-left: 20px;
            color: #000000 !important;
        }

        /* ===== ТАБЛИЦА ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            text-align: left;
            color: #000000 !important;
        }
        th {
            background: #f8f9fc;
            color: #000000 !important;
            font-weight: 700;
        }
        td strong {
            color: #000000 !important;
        }

        /* ===== КНОПКИ ===== */
        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
        }
        .btn-primary { background: #1e5eff; color: white !important; }
        .btn-danger { background: #dc3545; color: white !important; }
        .btn-success { background: #28a745; color: white !important; }
        .btn-warning { background: #ffc107; color: #000000 !important; }
        .btn-secondary { background: #6c757d; color: white !important; }
        .btn:hover { opacity: 0.8; }

        .btn-cart-add {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 25px;
            background: #28a745;
            color: white !important;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }

        /* ===== ПУСТАЯ КОРЗИНА ===== */
        .empty {
            text-align: center;
            padding: 60px;
            color: #999 !important;
        }
        .empty p {
            color: #999 !important;
        }
        .empty .btn {
            color: white !important;
        }

        /* ===== ИТОГО ===== */
        .total {
            font-size: 24px;
            font-weight: 700;
            text-align: right;
            margin-top: 20px;
            color: #000000 !important;
        }

        /* ===== ГИБКИЙ КОНТЕЙНЕР ===== */
        .flex {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        /* ===== ССЫЛКА НАЗАД ===== */
        .back-link {
            color: #1e5eff !important;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="user-info">
            <span>👤 <span class="name"><?php echo htmlspecialchars($_SESSION['username']); ?></span> (<?php echo $_SESSION['role']; ?>)</span>
            <a href="profile.php">← Профиль</a>
        </div>

        <h1>🛒 Корзина</h1>

        <?php if (empty($cart_items)): ?>
            <div class="empty">
                <p style="font-size: 48px; margin-bottom: 20px;">🛒</p>
                <p>Корзина пуста</p>
                <a href="../index.html" class="btn btn-primary" style="margin-top: 20px;">Перейти к каталогу</a>
            </div>
        <?php else: ?>
            <table>
                <tr>
                    <th>Университет</th>
                    <th>Страна</th>
                    <th>Цена</th>
                    <th>Кол-во</th>
                    <th>Сумма</th>
                    <th></th>
                </tr>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($item['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($item['country']); ?></td>
                        <td><?php echo number_format($item['price'], 0, ',', ' '); ?> €</td>
                        <td>
                            <div class="flex" style="gap: 5px;">
                                <a href="?decrease=<?php echo $item['id']; ?>" class="btn btn-warning" style="padding: 5px 10px; font-size: 12px;">−</a>
                                <span style="min-width: 30px; text-align: center;"><?php echo $item['quantity']; ?></span>
                                <a href="?add=<?php echo $item['id']; ?>" class="btn btn-success" style="padding: 5px 10px; font-size: 12px;">+</a>
                            </div>
                        </td>
                        <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', ' '); ?> €</td>
                        <td><a href="?remove=<?php echo $item['id']; ?>" class="btn btn-danger" style="padding: 5px 12px; font-size: 12px;">✕</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div class="total">Итого: <?php echo number_format($total, 0, ',', ' '); ?> €</div>

            <div class="flex-between">
                <div class="flex">
                    <a href="?clear=1" class="btn btn-danger" onclick="return confirm('Очистить корзину?')">🗑️ Очистить</a>
                    <a href="../index.html" class="btn btn-secondary">← Продолжить выбор</a>
                </div>
                <a href="#" class="btn btn-success" style="padding: 12px 30px;">✅ Оформить заказ</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>