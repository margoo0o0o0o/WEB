<?php
/**
 * Задание 5: Фильтрация товаров через GET-параметры
 * Реализована фильтрация по цене и категории
 */

// Массив товаров (минимум 7 товаров)
$products = [
    ['name' => 'Компьютер', 'category' => 'электроника', 'price' => 55000],
    ['name' => 'Книга PHP', 'category' => 'книги', 'price' => 1200],
    ['name' => 'Мышь', 'category' => 'электроника', 'price' => 1500],
    ['name' => 'Книга JavaScript', 'category' => 'книги', 'price' => 1100],
    ['name' => 'Клавиатура', 'category' => 'электроника', 'price' => 2500],
    ['name' => 'Монитор', 'category' => 'электроника', 'price' => 18000],
    ['name' => 'Книга Python', 'category' => 'книги', 'price' => 1350],
    ['name' => 'Наушники', 'category' => 'электроника', 'price' => 3200],
    ['name' => 'Книга SQL', 'category' => 'книги', 'price' => 950]
];

// Получаем уникальные категории для выпадающего списка
$categories = array_unique(array_column($products, 'category'));
sort($categories);

// Функция для фильтрации товаров
function filterProducts($products, $minPrice, $maxPrice, $category) {
    $filtered = [];
    
    foreach ($products as $product) {
        // Фильтр по цене
        if ($minPrice !== null && $product['price'] < $minPrice) continue;
        if ($maxPrice !== null && $product['price'] > $maxPrice) continue;
        
        // Фильтр по категории
        if (!empty($category) && $product['category'] !== $category) continue;
        
        $filtered[] = $product;
    }
    
    return $filtered;
}

// Получаем параметры фильтрации из GET-запроса
$minPrice = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
$maxPrice = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;
$category = $_GET['category'] ?? '';

// Применяем фильтрацию
$filteredProducts = filterProducts($products, $minPrice, $maxPrice, $category);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог товаров</title>
</head>
<body>
    <h2>Каталог товаров</h2>
    
    <!-- Форма фильтрации -->
    <form method="GET" action="">
        <p>
            Цена от: <input type="number" name="min_price" value="<?php echo htmlspecialchars($_GET['min_price'] ?? ''); ?>" step="100">
            до: <input type="number" name="max_price" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>" step="100">
        </p>
        <p>
            Категория:
            <select name="category">
                <option value="">Все категории</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>" 
                        <?php echo ($category == $cat) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Применить</button>
        </p>
    </form>
    
    <!-- Быстрые ссылки -->
    <p>
        <strong>Быстрые фильтры:</strong>
        <a href="?min_price=1000">Товары дороже 1000 руб.</a> |
        <a href="?category=книги">Книги</a> |
        <a href="?category=электроника">Электроника</a> |
        <a href="?">Сбросить</a>
    </p>
    
    <hr>
    
    <!-- Таблица товаров -->
    <h3>Товары (<?php echo count($filteredProducts); ?> найденных)</h3>
    
    <?php if (empty($filteredProducts)): ?>
        <p style="color:red;">Товаров не найдено.</p>
    <?php else: ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>Название</th>
                <th>Категория</th>
                <th>Цена (руб.)</th>
            </tr>
            <?php foreach ($filteredProducts as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                    <td><?php echo number_format($product['price'], 0, ',', ' '); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>