<?php
/**
 * КАТАЛОГ УНИВЕРСИТЕТОВ ИЗ БД (подключается в index.html)
 */

// ============================================
// ПОДКЛЮЧЕНИЕ К БАЗЕ ДАННЫХ
// ============================================

$host = '127.0.0.1';   // или 'localhost'
$user = 'root';
$password = '05rn05';        // у тебя пустой пароль
$dbname = 'study_moov';

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    echo '<div class="db-error">⚠️ База данных временно недоступна.</div>';
    return;
}

mysqli_set_charset($conn, 'utf8');

// Получаем параметры фильтрации
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$country = isset($_GET['country']) ? trim($_GET['country']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 4;
$offset = ($page - 1) * $limit;

// Список стран для фильтра
$countriesResult = mysqli_query($conn, "SELECT DISTINCT country FROM universities ORDER BY country");
$countries = [];
while ($row = mysqli_fetch_assoc($countriesResult)) {
    $countries[] = $row['country'];
}

// Строим WHERE
$where = "WHERE 1=1";
if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $where .= " AND name LIKE '%$search%'";
}
if (!empty($country)) {
    $country = mysqli_real_escape_string($conn, $country);
    $where .= " AND country = '$country'";
}

// Количество записей
$countResult = mysqli_query($conn, "SELECT COUNT(*) as total FROM universities $where");
$totalRow = mysqli_fetch_assoc($countResult);
$total = $totalRow['total'];
$pages = ceil($total / $limit);

// Сортировка
$allowedSort = ['name', 'country', 'price', 'rating'];
$sort = in_array($sort, $allowedSort) ? $sort : 'name';
$order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

// Запрос с сортировкой и пагинацией
$query = "SELECT * FROM universities $where ORDER BY $sort $order LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
$universities = [];
while ($row = mysqli_fetch_assoc($result)) {
    $universities[] = $row;
}

mysqli_close($conn);
?>

<!-- ===== КАТАЛОГ УНИВЕРСИТЕТОВ ===== -->
<div class="catalog-on-main">
    <div class="container">
        <h2>Каталог университетов</h2>

        <!-- ФИЛЬТРЫ -->
        <div class="filter-box">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Поиск по названию..." value="<?php echo htmlspecialchars($search); ?>">
                <select name="country">
                    <option value="">Все страны</option>
                    <?php foreach ($countries as $c): ?>
                        <option value="<?php echo htmlspecialchars($c); ?>" <?php echo ($country == $c) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Применить</button>
                <a href="?" class="reset-link">Сбросить</a>
            </form>

            <!-- СОРТИРОВКА -->
            <div class="sort-links">
                <span style="color:#999;font-size:14px;">Сортировка:</span>
                <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'name', 'order' => ($sort == 'name' && $order == 'ASC') ? 'DESC' : 'ASC'])); ?>" class="<?php echo ($sort == 'name') ? 'active' : ''; ?>">
                    По названию <?php echo ($sort == 'name') ? ($order == 'ASC' ? '↑' : '↓') : ''; ?>
                </a>
                <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'country', 'order' => ($sort == 'country' && $order == 'ASC') ? 'DESC' : 'ASC'])); ?>" class="<?php echo ($sort == 'country') ? 'active' : ''; ?>">
                    По стране <?php echo ($sort == 'country') ? ($order == 'ASC' ? '↑' : '↓') : ''; ?>
                </a>
                <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'price', 'order' => ($sort == 'price' && $order == 'ASC') ? 'DESC' : 'ASC'])); ?>" class="<?php echo ($sort == 'price') ? 'active' : ''; ?>">
                    По цене <?php echo ($sort == 'price') ? ($order == 'ASC' ? '↑' : '↓') : ''; ?>
                </a>
                <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'rating', 'order' => ($sort == 'rating' && $order == 'ASC') ? 'DESC' : 'ASC'])); ?>" class="<?php echo ($sort == 'rating') ? 'active' : ''; ?>">
                    По рейтингу <?php echo ($sort == 'rating') ? ($order == 'ASC' ? '↑' : '↓') : ''; ?>
                </a>
            </div>
        </div>

        <!-- СТАТИСТИКА -->
        <div class="stats">Найдено: <strong><?php echo $total; ?></strong> университетов</div>

        <!-- СПИСОК УНИВЕРСИТЕТОВ -->
        <?php if (empty($universities)): ?>
            <div class="no-data">Университетов не найдено. Попробуйте изменить параметры поиска.</div>
        <?php else: ?>
            <?php foreach ($universities as $uni): ?>
                <div class="uni-card">
                    <h3><?php echo htmlspecialchars($uni['name']); ?></h3>
                    <div class="meta">📍 <?php echo htmlspecialchars($uni['country']); ?>, <?php echo htmlspecialchars($uni['city']); ?></div>
                    <div class="meta">⭐ Рейтинг: <?php echo $uni['rating']; ?> / 5</div>
                    <div class="meta">🎓 Программы: <?php echo htmlspecialchars($uni['programs']); ?></div>
                    <div class="meta">🌍 Языки: <?php echo htmlspecialchars($uni['languages']); ?></div>
                    <div class="price">💰 от <?php echo number_format($uni['price'], 0, ',', ' '); ?> €/год</div>
                    <?php if (!empty($uni['description'])): ?>
                        <div class="desc"><?php echo htmlspecialchars($uni['description']); ?></div>
                    <?php endif; ?>
                    
                    <!-- ========================================================= -->
                    <!-- КНОПКА "ДОБАВИТЬ В КОРЗИНУ" -->
                    <!-- ========================================================= -->
                    <a href="php/cart.php?add=<?php echo $uni['id']; ?>&name=<?php echo urlencode($uni['name']); ?>&price=<?php echo $uni['price']; ?>" 
                       style="display: inline-block; margin-top: 15px; padding: 10px 25px; background: #28a745; color: white; border: none; border-radius: 8px; text-decoration: none; font-weight: 600; cursor: pointer;">
                        🛒 Добавить в корзину
                    </a>
                </div>
            <?php endforeach; ?>

            <!-- ПАГИНАЦИЯ -->
            <?php if ($pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">←</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $pages): ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">→</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<style>
/* ===== СТИЛИ ДЛЯ КАТАЛОГА ===== */
.catalog-on-main { padding: 60px 0; background: #f5f7fa; }
.catalog-on-main .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
.catalog-on-main h2 { font-size: 36px; font-weight: 900; color: #111; border-left: 5px solid #1e5eff; padding-left: 20px; margin-bottom: 30px; }

.filter-box { background: white; padding: 25px; border-radius: 16px; margin-bottom: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
.filter-box form { display: flex; flex-wrap: wrap; gap: 15px; align-items: center; }
.filter-box input, .filter-box select { padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; flex: 1; min-width: 150px; }
.filter-box button { background: #1e5eff; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: 600; }
.filter-box button:hover { background: #0a4ae6; }
.filter-box .reset-link { color: #1e5eff; text-decoration: none; font-weight: 600; }

.sort-links { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 15px; }
.sort-links a { color: #1e5eff; text-decoration: none; font-size: 14px; }
.sort-links a.active { font-weight: 700; text-decoration: underline; }

.stats { color: #666; margin-bottom: 20px; font-size: 14px; }

.uni-card { background: white; padding: 25px; border-radius: 16px; margin-bottom: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-left: 4px solid #1e5eff; }
.uni-card h3 { font-size: 22px; color: #1e5eff; margin: 0 0 10px 0; }
.uni-card .meta { color: #666; font-size: 14px; margin: 5px 0; }
.uni-card .price { font-size: 22px; font-weight: 700; color: #28a745; margin: 10px 0; }
.uni-card .desc { color: #555; margin-top: 10px; line-height: 1.6; }

.pagination { display: flex; gap: 8px; justify-content: center; margin-top: 30px; flex-wrap: wrap; }
.pagination a, .pagination span { padding: 10px 16px; background: white; border: 1px solid #ddd; border-radius: 8px; text-decoration: none; color: #333; }
.pagination .active { background: #1e5eff; color: white; border-color: #1e5eff; }
.pagination a:hover { background: #f0f0f0; }

.no-data { text-align: center; padding: 40px; color: #999; }
.db-error { background: #f8d7da; color: #721c24; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; }
</style>