<?php
session_start();

// Инициализация корзины
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Добавление в корзину
if (isset($_GET['add'])) {
    $id = $_GET['add'];
    $name = $_GET['name'];
    $price = $_GET['price'];
    
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty']++;
    } else {
        $_SESSION['cart'][$id] = ['name' => $name, 'price' => $price, 'qty' => 1];
    }
    header('Location: cart.php');
    exit;
}

// Удаление из корзины
if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    header('Location: cart.php');
    exit;
}

// Очистка корзины
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    header('Location: cart.php');
    exit;
}

// Оформление заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $comment = $_POST['comment'];
    $total = 0;
    $items = [];
    
    foreach ($_SESSION['cart'] as $id => $item) {
        $subtotal = $item['price'] * $item['qty'];
        $total += $subtotal;
        $items[] = $item['name'] . " (x{$item['qty']}) - " . number_format($subtotal, 0, ',', ' ') . " €";
    }
    
    $orderData = date('Y-m-d H:i:s') . " | $name | $email | $phone | " . implode(", ", $items) . " | Итого: " . number_format($total, 0, ',', ' ') . " € | $comment\n";
    file_put_contents(__DIR__ . '/data/orders.txt', $orderData, FILE_APPEND);
    
    $_SESSION['cart'] = [];
    $order_success = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Корзина</title>
    <style>
        body { font-family: Arial; background: #f5f7fa; padding: 40px; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        .btn { padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; color: white; }
        .btn-danger { background: #dc3545; }
        .btn-success { background: #28a745; }
        .btn-primary { background: #1e5eff; }
        .btn-warning { background: #ffc107; color: #333; }
        .total { font-size: 24px; font-weight: 700; text-align: right; margin-top: 20px; }
        .back-link { display: inline-block; margin-top: 20px; color: #1e5eff; text-decoration: none; }
        .form-group { margin-bottom: 15px; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; }
        .order-form { margin-top: 20px; border-top: 2px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛒 Корзина</h1>
        
        <?php if (isset($order_success)): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                ✅ Заказ оформлен! Спасибо!
            </div>
        <?php endif; ?>
        
        <?php if (empty($_SESSION['cart'])): ?>
            <p>Корзина пуста</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Университет</th>
                    <th>Цена</th>
                    <th>Кол-во</th>
                    <th>Сумма</th>
                    <th></th>
                </tr>
                <?php $total = 0; ?>
                <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                    <?php $subtotal = $item['price'] * $item['qty']; $total += $subtotal; ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo number_format($item['price'], 0, ',', ' '); ?> €</td>
                        <td><?php echo $item['qty']; ?></td>
                        <td><?php echo number_format($subtotal, 0, ',', ' '); ?> €</td>
                        <td><a href="?remove=<?php echo $id; ?>" class="btn btn-danger">Удалить</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            
            <div class="total">Итого: <?php echo number_format($total, 0, ',', ' '); ?> €</div>
            
            <div style="margin: 20px 0; display: flex; gap: 10px;">
                <a href="?clear=1" class="btn btn-danger">Очистить корзину</a>
            </div>
            
            <!-- ФОРМА ОФОРМЛЕНИЯ ЗАКАЗА -->
            <div class="order-form">
                <h3>Оформление заказа</h3>
                <form method="POST">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Ваше имя *" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Ваш email *" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone" placeholder="Телефон">
                    </div>
                    <div class="form-group">
                        <textarea name="comment" rows="3" placeholder="Комментарий к заказу"></textarea>
                    </div>
                    <button type="submit" name="order" class="btn btn-success" style="padding: 12px 30px;">Оформить заказ</button>
                </form>
            </div>
        <?php endif; ?>
        
        <a href="../index.html" class="back-link">← На главную</a>
    </div>
</body>
</html>