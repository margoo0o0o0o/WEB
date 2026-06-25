<!-- ============================================================ -->
<!-- КАТАЛОГ УНИВЕРСИТЕТОВ -->
<!-- ============================================================ -->

<div style="padding: 60px 0; background: #f5f7fa;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        
        <h2 style="font-size: 36px; font-weight: 900; color: #111; border-left: 5px solid #1e5eff; padding-left: 20px; margin-bottom: 30px;">
            Каталог университетов
        </h2>

        <!-- ============================================================ -->
        <!-- ФИЛЬТРЫ И ПОИСК -->
        <!-- ============================================================ -->
        <div style="background: white; padding: 25px; border-radius: 16px; margin-bottom: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
            
            <form method="GET" action="" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center;">
                <!-- Скрытый параметр page, чтобы форма не сбрасывала страницу -->
                <input type="hidden" name="page" value="catalog">
                
                <!-- Поиск -->
                <input type="text" name="search" placeholder="Поиск по названию..." 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       style="padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; flex: 1; min-width: 150px;">
                
                <!-- Фильтр по стране -->
                <select name="country" style="padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; flex: 1; min-width: 150px;">
                    <option value="">Все страны</option>
                    <?php foreach ($countries as $c): ?>
                        <option value="<?php echo htmlspecialchars($c); ?>" <?php echo ($country == $c) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <!-- Кнопки -->
                <button type="submit" style="background: #1e5eff; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    Применить
                </button>
                <a href="app.php?page=catalog" style="color: #1e5eff; text-decoration: none; font-weight: 600;">
                    Сбросить
                </a>
            </form>

            <!-- ============================================================ -->
            <!-- СОРТИРОВКА -->
            <!-- ============================================================ -->
            <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 15px;">
                <span style="color:#999;font-size:14px;">Сортировка:</span>
                
                <a href="app.php?page=catalog&search=<?php echo urlencode($search); ?>&country=<?php echo urlencode($country); ?>&sort=name&order=<?php echo ($sort == 'name' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>" 
                   style="<?php echo ($sort == 'name') ? 'font-weight:700;text-decoration:underline;' : ''; ?> color:#1e5eff;text-decoration:none;font-size:14px;">
                    По названию <?php echo ($sort == 'name') ? ($order == 'ASC' ? '↑' : '↓') : ''; ?>
                </a>
                
                <a href="app.php?page=catalog&search=<?php echo urlencode($search); ?>&country=<?php echo urlencode($country); ?>&sort=country&order=<?php echo ($sort == 'country' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>" 
                   style="<?php echo ($sort == 'country') ? 'font-weight:700;text-decoration:underline;' : ''; ?> color:#1e5eff;text-decoration:none;font-size:14px;">
                    По стране <?php echo ($sort == 'country') ? ($order == 'ASC' ? '↑' : '↓') : ''; ?>
                </a>
                
                <a href="app.php?page=catalog&search=<?php echo urlencode($search); ?>&country=<?php echo urlencode($country); ?>&sort=price&order=<?php echo ($sort == 'price' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>" 
                   style="<?php echo ($sort == 'price') ? 'font-weight:700;text-decoration:underline;' : ''; ?> color:#1e5eff;text-decoration:none;font-size:14px;">
                    По цене <?php echo ($sort == 'price') ? ($order == 'ASC' ? '↑' : '↓') : ''; ?>
                </a>
                
                <a href="app.php?page=catalog&search=<?php echo urlencode($search); ?>&country=<?php echo urlencode($country); ?>&sort=rating&order=<?php echo ($sort == 'rating' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>" 
                   style="<?php echo ($sort == 'rating') ? 'font-weight:700;text-decoration:underline;' : ''; ?> color:#1e5eff;text-decoration:none;font-size:14px;">
                    По рейтингу <?php echo ($sort == 'rating') ? ($order == 'ASC' ? '↑' : '↓') : ''; ?>
                </a>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- СТАТИСТИКА -->
        <!-- ============================================================ -->
        <div style="color:#666; margin-bottom: 20px; font-size: 14px;">
            Найдено: <strong><?php echo $total; ?></strong> университетов
        </div>

        <!-- ============================================================ -->
        <!-- СПИСОК УНИВЕРСИТЕТОВ -->
        <!-- ============================================================ -->
        <?php if (empty($universities)): ?>
            <div style="text-align:center;padding:40px;color:#999;">
                Университетов не найдено. Попробуйте изменить параметры поиска.
            </div>
        <?php else: ?>
            <?php foreach ($universities as $uni): ?>
                <div style="background: white; padding: 25px; border-radius: 16px; margin-bottom: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-left: 4px solid #1e5eff;">
                    
                    <h3 style="font-size: 22px; color: #1e5eff; margin: 0 0 10px 0;">
                        <?php echo htmlspecialchars($uni['name']); ?>
                    </h3>
                    
                    <div style="color:#666;font-size:14px;margin:5px 0;">
                        📍 <?php echo htmlspecialchars($uni['country']); ?>, <?php echo htmlspecialchars($uni['city']); ?>
                    </div>
                    
                    <div style="color:#666;font-size:14px;margin:5px 0;">
                        ⭐ Рейтинг: <?php echo $uni['rating']; ?> / 5
                    </div>
                    
                    <div style="color:#666;font-size:14px;margin:5px 0;">
                        🎓 Программы: <?php echo htmlspecialchars($uni['programs']); ?>
                    </div>
                    
                    <div style="color:#666;font-size:14px;margin:5px 0;">
                        🌍 Языки: <?php echo htmlspecialchars($uni['languages']); ?>
                    </div>
                    
                    <div style="font-size: 22px; font-weight: 700; color: #28a745; margin: 10px 0;">
                        💰 от <?php echo number_format($uni['price'], 0, ',', ' '); ?> €/год
                    </div>
                    
                    <?php if (!empty($uni['description'])): ?>
                        <div style="color:#555;margin-top:10px;line-height:1.6;">
                            <?php echo htmlspecialchars($uni['description']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- ============================================================ -->
                    <!-- КНОПКА ДОБАВЛЕНИЯ В КОРЗИНУ -->
                    <!-- ============================================================ -->
                    <a href="app.php?page=cart_add&add=<?php echo $uni['id']; ?>&name=<?php echo urlencode($uni['name']); ?>&price=<?php echo $uni['price']; ?>" 
                       style="display: inline-block; margin-top: 15px; padding: 10px 25px; background: #28a745; color: white; border: none; border-radius: 8px; text-decoration: none; font-weight: 600; cursor: pointer;">
                        🛒 Добавить в корзину
                    </a>
                    
                </div>
            <?php endforeach; ?>

            <!-- ============================================================ -->
            <!-- ПАГИНАЦИЯ -->
            <!-- ============================================================ -->
            <?php if ($pages > 1): ?>
                <div style="display: flex; gap: 8px; justify-content: center; margin-top: 30px; flex-wrap: wrap;">
                    
                    <?php if ($page > 1): ?>
                        <a href="app.php?page=catalog&page_num=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&country=<?php echo urlencode($country); ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>" 
                           style="padding:10px 16px;background:white;border:1px solid #ddd;border-radius:8px;text-decoration:none;color:#333;">
                            ←
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span style="padding:10px 16px;background:#1e5eff;color:white;border:1px solid #1e5eff;border-radius:8px;">
                                <?php echo $i; ?>
                            </span>
                        <?php else: ?>
                            <a href="app.php?page=catalog&page_num=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&country=<?php echo urlencode($country); ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>" 
                               style="padding:10px 16px;background:white;border:1px solid #ddd;border-radius:8px;text-decoration:none;color:#333;">
                                <?php echo $i; ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $pages): ?>
                        <a href="app.php?page=catalog&page_num=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&country=<?php echo urlencode($country); ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>" 
                           style="padding:10px 16px;background:white;border:1px solid #ddd;border-radius:8px;text-decoration:none;color:#333;">
                            →
                        </a>
                    <?php endif; ?>
                    
                </div>
            <?php endif; ?>
            
        <?php endif; ?>
        
    </div>
</div>