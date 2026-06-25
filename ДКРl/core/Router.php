<?php
/**
 * КЛАСС МАРШРУТИЗАТОР
 * 
 * Задача: сопоставить URL с контроллером и методом
 * Пример: page=catalog → UniversityController->catalog()
 */

namespace Core;

class Router {
    
    /**
     * Массив маршрутов
     * [
     *   'catalog' => ['controller' => 'UniversityController', 'method' => 'catalog'],
     *   'cart'    => ['controller' => 'CartController', 'method' => 'index']
     * ]
     */
    private $routes = [];
    
    /**
     * Добавление нового маршрута
     * 
     * @param string $route      — название страницы (из GET-параметра page)
     * @param string $controller — имя контроллера
     * @param string $method     — метод контроллера
     */
    public function add($route, $controller, $method) {
        $this->routes[$route] = [
            'controller' => $controller,
            'method' => $method
        ];
    }
    
    /**
     * Запуск маршрутизации
     * 
     * @param string $route — название страницы
     */
    public function dispatch($route) {
        
        // Проверяем, есть ли такой маршрут
        if (isset($this->routes[$route])) {
            
            $action = $this->routes[$route];
            $controllerName = 'App\\Controllers\\' . $action['controller'];
            
            // Проверяем, существует ли класс контроллера
            if (class_exists($controllerName)) {
                
                $controller = new $controllerName();
                $method = $action['method'];
                
                // Проверяем, существует ли метод
                if (method_exists($controller, $method)) {
                    $controller->$method(); // Вызываем метод
                    return;
                }
            }
        }
        
        // Если маршрут не найден — показываем каталог (по умолчанию)
        $controller = new \App\Controllers\UniversityController();
        $controller->catalog();
    }
}