<?php
/**
 * Задание 4: Форма заказа пиццы
 * Обработка радиокнопок, чекбоксов, выпадающего списка и textarea
 */

$pizzaPrices = [
    'small' => 250,
    'medium' => 350,
    'large' => 450
];

$availableToppings = [
    'cheese' => 'Сыр',
    'mushrooms' => 'Грибы',
    'sausage' => 'Колбаса',
    'olives' => 'Оливки'
];

$orderProcessed = false;
$orderData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderProcessed = true;
    
    $orderData['size'] = $_POST['size'] ?? '';
    $orderData['sizePrice'] = $pizzaPrices[$orderData['size']] ?? 0;
    $orderData['toppings'] = $_POST['toppings'] ?? [];
    $orderData['comment'] = trim($_POST['comment'] ?? '');
    $orderData['delivery'] = $_POST['delivery'] ?? '';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заказ пиццы</title>
</head>
<body>
    <h2>Заказ пиццы</h2>
    
    <form method="POST" action="">
        <p><strong>Выберите размер:</strong></p>
        <p>
            <input type="radio" name="size" value="small" <?php echo (!isset($_POST['size']) || $_POST['size'] == 'small') ? 'checked' : ''; ?>> Маленькая (250 руб.)<br>
            <input type="radio" name="size" value="medium" <?php echo (isset($_POST['size']) && $_POST['size'] == 'medium') ? 'checked' : ''; ?>> Средняя (350 руб.)<br>
            <input type="radio" name="size" value="large" <?php echo (isset($_POST['size']) && $_POST['size'] == 'large') ? 'checked' : ''; ?>> Большая (450 руб.)
        </p>
        
        <p><strong>Топпинги:</strong></p>
        <p>
            <?php foreach ($availableToppings as $value => $label): ?>
                <input type="checkbox" name="toppings[]" value="<?php echo $value; ?>"
                    <?php echo (isset($_POST['toppings']) && in_array($value, $_POST['toppings'])) ? 'checked' : ''; ?>>
                <?php echo $label; ?><br>
            <?php endforeach; ?>
        </p>
        
        <p>
            <strong>Комментарий:</strong><br>
            <textarea name="comment" rows="3" cols="40"><?php echo htmlspecialchars($_POST['comment'] ?? '', ENT_QUOTES); ?></textarea>
        </p>
        
        <p>
            <strong>Способ доставки:</strong><br>
            <select name="delivery">
                <option value="pickup" <?php echo (isset($_POST['delivery']) && $_POST['delivery'] == 'pickup') ? 'selected' : ''; ?>>Самовывоз</option>
                <option value="courier" <?php echo (isset($_POST['delivery']) && $_POST['delivery'] == 'courier') ? 'selected' : ''; ?>>Курьером</option>
            </select>
        </p>
        
        <button type="submit">Оформить заказ</button>
    </form>
    
    <?php if ($orderProcessed): ?>
        <hr>
        <h3>Ваш заказ:</h3>
        
        <?php if (empty($orderData['size'])): ?>
            <p style="color:red;">Ошибка: Размер пиццы не выбран</p>
        <?php else: ?>
            <p><strong>Размер:</strong> 
                <?php 
                $sizeNames = ['small' => 'Маленькая', 'medium' => 'Средняя', 'large' => 'Большая'];
                echo htmlspecialchars($sizeNames[$orderData['size']]); 
                ?> (<?php echo $orderData['sizePrice']; ?> руб.)
            </p>
            
            <p><strong>Топпинги:</strong>
                <?php if (empty($orderData['toppings'])): ?>
                    Не выбраны
                <?php else: ?>
                    <?php 
                    $selectedToppings = [];
                    foreach ($orderData['toppings'] as $topping) {
                        if (isset($availableToppings[$topping])) {
                            $selectedToppings[] = htmlspecialchars($availableToppings[$topping]);
                        }
                    }
                    echo implode(', ', $selectedToppings);
                    ?>
                <?php endif; ?>
            </p>
            
            <p><strong>Комментарий:</strong> 
                <?php echo empty($orderData['comment']) ? 'Нет' : htmlspecialchars($orderData['comment']); ?>
            </p>
            
            <p><strong>Доставка:</strong>
                <?php echo $orderData['delivery'] == 'pickup' ? 'Самовывоз' : 'Курьером'; ?>
            </p>
            
            <hr>
            <p><strong>Итого:</strong> <?php echo $orderData['sizePrice']; ?> руб.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>