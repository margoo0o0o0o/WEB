<?php
/**
 * КОНТРОЛЛЕР УНИВЕРСИТЕТОВ
 * 
 * Обрабатывает запросы, связанные с каталогом университетов
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\University;

class UniversityController extends Controller {
    
    /**
     * @var University $universityModel — модель университетов
     */
    private $universityModel;
    
    /**
     * Конструктор — создаём модель
     */
    public function __construct() {
        $this->universityModel = new University();
    }
    
    /**
     * ОТОБРАЖЕНИЕ КАТАЛОГА
     * 
     * Читает GET-параметры:
     * - search   — поиск
     * - country  — фильтр по стране
     * - sort     — поле для сортировки
     * - order    — направление сортировки
     * - page_num — номер страницы
     */
    public function catalog() {
        
        // ============================================================
        // 1. ПОЛУЧАЕМ ПАРАМЕТРЫ ИЗ URL
        // ============================================================
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $country = isset($_GET['country']) ? trim($_GET['country']) : '';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'name';
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
        $page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
        $limit = 4;                          // 4 университета на страницу
        $offset = ($page - 1) * $limit;      // Смещение для пагинации
        
        // ============================================================
        // 2. ПОЛУЧАЕМ ДАННЫЕ ИЗ МОДЕЛИ
        // ============================================================
        $total = $this->universityModel->getCount($search, $country);
        $pages = ceil($total / $limit);
        $universities = $this->universityModel->getWithFilters($search, $country, $sort, $order, $limit, $offset);
        $countries = $this->universityModel->getCountries();
        
        // ============================================================
        // 3. ПЕРЕДАЁМ ДАННЫЕ В ВИД
        // ============================================================
        $this->view('university/catalog', [
            'universities' => $universities,
            'countries' => $countries,
            'search' => $search,
            'country' => $country,
            'sort' => $sort,
            'order' => $order,
            'page' => $page,
            'pages' => $pages,
            'total' => $total
        ]);
    }
}