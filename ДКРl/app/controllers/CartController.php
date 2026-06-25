<?php
/**
 * КОНТРОЛЛЕР КОРЗИНЫ
 * 
 * Обрабатывает все действия с корзиной:
 * - просмотр
 * - добавление
 * - удаление
 * - очистка
 * - оформление заказа
 */

namespace App\Controllers;

use Core\Controller;

class CartController extends Controller {
    
    /**
     * Конструктор — инициализируем сессию и корзину
     */
    public function __construct() {
        // Запускаем сессию, если она ещё не запущена
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Создаём пустую корзину, если её ещё нет
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }
    
    /**
     * ОТОБРАЖЕНИЕ КОРЗИНЫ
     */
    public function index() {
        $cart = $_SESSION['cart'];
        $total = 0;
        
        // Считаем общую стоимость
        foreach ($cart as $id => $item) {
            $total += $item['price'] * $item['qty'];
        }
        
        $this->view('cart/index', [
            'cart' => $cart,
            'total' => $total
        ]);
    }
    
    /**
     * ДОБАВЛЕНИЕ В КОРЗИНУ
     * 
     * Пример: app.php?page=cart_add&add=1&name=Оксфорд&price=35000
     */
    public function add() {
        $id = isset($_GET['add']) ? (int)$_GET['add'] : 0;
        $name = isset($_GET['name']) ? $_GET['name'] : '';
        $price = isset($_GET['price']) ? (float)$_GET['price'] : 0;
        
        if ($id > 0) {
            if (isset($_SESSION['cart'][$id])) {
                // Если уже есть — увеличиваем количество
                $_SESSION['cart'][$id]['qty']++;
            } else {
                // Если нет — добавляем новый товар
                $_SESSION['cart'][$id] = [
                    'name' => $name,
                    'price' => $price,
                    'qty' => 1
                ];
            }
        }
        
        $this->redirect('app.php?page=cart');
    }
    
    /**
     * УДАЛЕНИЕ ИЗ КОРЗИНЫ
     */
    public function remove() {
        $id = isset($_GET['remove']) ? (int)$_GET['remove'] : 0;
        if ($id > 0 && isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        $this->redirect('app.php?page=cart');
    }
    
    /**
     * ОЧИСТКА КОРЗИНЫ
     */
    public function clear() {
        $_SESSION['cart'] = [];
        $this->redirect('app.php?page=cart');
    }
    
    /**
     * ОФОРМЛЕНИЕ ЗАКАЗА
     * 
     * Сохраняет заказ в файл data/orders.txt
     */
    public function order() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Получаем данные из формы
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $comment = $_POST['comment'] ?? '';
            $total = 0;
            $items = [];
            
            // Формируем список товаров
            foreach ($_SESSION['cart'] as $id => $item) {
                $subtotal = $item['price'] * $item['qty'];
                $total += $subtotal;
                $items[] = $item['name'] . " (x{$item['qty']}) - " . number_format($subtotal, 0, ',', ' ') . " €";
            }
            
            // Сохраняем заказ в файл
            $orderData = date('Y-m-d H:i:s') . " | $name | $email | $phone | " . implode(", ", $items) . " | Итого: " . number_format($total, 0, ',', ' ') . " € | $comment\n";
            file_put_contents(__DIR__ . '/../data/orders.txt', $orderData, FILE_APPEND);
            
            // Очищаем корзину
            $_SESSION['cart'] = [];
            $_SESSION['order_success'] = true;
            
            $this->redirect('app.php?page=cart');
        }
    }
}