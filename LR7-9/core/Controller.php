<?php
/**
 * БАЗОВЫЙ КОНТРОЛЛЕР
 * 
 * Все контроллеры наследуются от этого класса
 * Содержит общие методы: view() и redirect()
 */

namespace Core;
abstract class Controller {
    
    /**
     * ОТОБРАЖЕНИЕ ВИДА (HTML-страницы)
     * 
     * @param string $view — путь к файлу вида (без .php)
     * @param array  $data — данные, которые передаются в вид
     * 
     * Пример: view('university/catalog', ['universities' => $list])
     * 
     * Алгоритм:
     * 1. extract($data) — превращает ключи массива в переменные
     *    ['universities' => $list] → $universities = $list
     * 2. ob_start() — начинает буферизацию вывода
     * 3. require_once ... — подключает файл вида
     * 4. $content = ob_get_clean() — забирает вывод из буфера
     * 5. Подключает layout.php, в который вставляется $content
     */
    protected function view($view, $data = []) {
        // Превращаем массив в переменные
        extract($data);
        
        // Захватываем вывод вида в буфер
        ob_start();
        require_once __DIR__ . '/../app/views/' . $view . '.php';
        $content = ob_get_clean();
        
        // Подключаем основной шаблон (layout)
        require_once __DIR__ . '/../app/views/layout.php';
    }
    
    /**
     * ПЕРЕНАПРАВЛЕНИЕ НА ДРУГУЮ СТРАНИЦУ
     * 
     * @param string $url — URL, на который нужно перенаправить
     * 
     * Пример: redirect('app.php?page=cart')
     */
    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
}