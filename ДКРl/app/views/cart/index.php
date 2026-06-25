<!-- ============================================================ -->
<!-- КОРЗИНА -->
<!-- ============================================================ -->

<div style="padding: 60px 0; background: #f5f7fa;">
    <div style="max-width: 900px; margin: 0 auto; padding: 0 20px;">
        
        <h2 style="font-size: 36px; font-weight: 900; color: #111; border-left: 5px solid #1e5eff; padding-left: 20px; margin-bottom: 30px;">
            🛒 Корзина
        </h2>

        <!-- ============================================================ -->
        <!-- СООБЩЕНИЕ ОБ УСПЕШНОМ ЗАКАЗЕ -->
        <!-- ============================================================ -->
        <?php if (isset($_SESSION['order_success']) && $_SESSION['order_success']): ?>
            <?php unset($_SESSION['order_success']); ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                ✅ Заказ оформлен! Спасибо!
            </div>
        <?php endif; ?>

        <!-- ============================================================ -->
        <!-- ПУСТАЯ КОРЗИНА -->
        <!-- ============================================================ -->
        <?php if (empty($cart)): ?>
            <div style="text-align:center;padding:60px;background:white;border-radius:16px;box-shadow:0 5px 20px rgba(0,0,0,0.05);">
                <p style="font-size:24px;color:#999;">🛒 Корзина пуста</p>
                <a href="app.php?page=catalog" style="display:inline-block;margin-top:20px;padding:12px 30px;background:#1e5eff;color:white;border-radius:8px;text-decoration:none;">
                    Перейти к каталогу
                </a>
            </div>
        <?php else: ?>
            
            <!-- ============================================================ -->
            <!-- ТАБЛИЦА С ТОВАРАМИ -->
            <!-- ============================================================ -->
            <div style="background:white;padding:25px;border-radius:16px;box-shadow:0 5px 20px rgba(0,0,0,0.05);">
                <table style="width:100%;border-collapse:collapse;">
                    <tr style="border-bottom:2px solid #eee;">
                        <th style="padding:12px;text-align:left;">Университет</th>
                        <th style="padding:12px;text-align:center;">Цена</th>
                        <th style="padding:12px;text-align:center;">Кол-во</th>
                        <th style="padding:12px;text-align:center;">Сумма</th>
                        <th style="padding:12px;text-align:center;"></th>
                    </tr>
                    
                    <?php $total = 0; ?>
                    <?php foreach ($cart as $id => $item): ?>
                        <?php $subtotal = $item['price'] * $item['qty']; $total += $subtotal; ?>
                        <tr style="border-bottom:1px solid #eee;">
                            <td style="padding:12px;"><?php echo htmlspecialchars($item['name']); ?></td>
                            <td style="padding:12px;text-align:center;"><?php echo number_format($item['price'], 0, ',', ' '); ?> €</td>
                            <td style="padding:12px;text-align:center;"><?php echo $item['qty']; ?></td>
                            <td style="padding:12px;text-align:center;font-weight:700;"><?php echo number_format($subtotal, 0, ',', ' '); ?> €</td>
                            <td style="padding:12px;text-align:center;">
                                <a href="app.php?page=cart_remove&remove=<?php echo $id; ?>" 
                                   style="color:#dc3545;text-decoration:none;font-weight:600;">
                                    ✕
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                
                <!-- ИТОГО -->
                <div style="text-align:right;margin-top:20px;font-size:24px;font-weight:700;">
                    Итого: <?php echo number_format($total, 0, ',', ' '); ?> €
                </div>
                
                <!-- КНОПКИ -->
                <div style="display:flex;gap:10px;margin-top:20px;flex-wrap:wrap;justify-content:space-between;align-items:center;">
                    <div style="display:flex;gap:10px;">
                        <a href="app.php?page=cart_clear" 
                           style="padding:10px 20px;background:#dc3545;color:white;border-radius:8px;text-decoration:none;font-weight:600;"
                           onclick="return confirm('Очистить корзину?')">
                            🗑️ Очистить
                        </a>
                        <a href="app.php?page=catalog" 
                           style="padding:10px 20px;background:#6c757d;color:white;border-radius:8px;text-decoration:none;font-weight:600;">
                            ← Продолжить выбор
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- ============================================================ -->
            <!-- ФОРМА ОФОРМЛЕНИЯ ЗАКАЗА -->
            <!-- ============================================================ -->
            <div style="background:white;padding:25px;border-radius:16px;margin-top:20px;box-shadow:0 5px 20px rgba(0,0,0,0.05);">
                <h3 style="font-size:22px;color:#333;margin-bottom:20px;">📝 Оформление заказа</h3>
                
                <form method="POST" action="app.php?page=cart_order">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                        <div>
                            <label style="display:block;font-weight:600;margin-bottom:5px;">Ваше имя *</label>
                            <input type="text" name="name" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;">
                        </div>
                        <div>
                            <label style="display:block;font-weight:600;margin-bottom:5px;">Ваш email *</label>
                            <input type="email" name="email" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;">
                        </div>
                    </div>
                    <div style="margin-top:15px;">
                        <label style="display:block;font-weight:600;margin-bottom:5px;">Телефон</label>
                        <input type="tel" name="phone" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;">
                    </div>
                    <div style="margin-top:15px;">
                        <label style="display:block;font-weight:600;margin-bottom:5px;">Комментарий к заказу</label>
                        <textarea name="comment" rows="3" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;"></textarea>
                    </div>
                    <button type="submit" style="margin-top:20px;padding:12px 30px;background:#28a745;color:white;border:none;border-radius:8px;font-size:16px;font-weight:600;cursor:pointer;">
                        ✅ Оформить заказ
                    </button>
                </form>
            </div>
            
        <?php endif; ?>
        
    </div>
</div>