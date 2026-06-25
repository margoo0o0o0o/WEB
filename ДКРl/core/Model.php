<?php
/**
 * БАЗОВАЯ МОДЕЛЬ
 * 
 * Все модели наследуются от этого класса
 * Содержит общие методы для работы с БД
 */

namespace Core;

use Config\Database;

abstract class Model {
    
    /**
     * @var object $db — подключение к БД
     * @var string $table — название таблицы (определяется в дочернем классе)
     */
    protected $db;
    protected $table;
    
    /**
     * Конструктор — получает подключение к БД из Database::getInstance()
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * ВЫПОЛНЕНИЕ SQL-ЗАПРОСА
     * 
     * @param string $sql — SQL-запрос
     * @return array|bool — результат запроса
     * 
     * Если это SELECT — возвращает массив с данными
     * Если это INSERT/UPDATE/DELETE — возвращает true/false
     */
    public function query($sql) {
        $result = mysqli_query($this->db, $sql);
        
        // Если это не SELECT — возвращаем результат как есть
        if (is_bool($result)) {
            return $result;
        }
        
        // Если это SELECT — возвращаем массив с данными
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    /**
     * ПОЛУЧЕНИЕ ВСЕХ ЗАПИСЕЙ ИЗ ТАБЛИЦЫ
     */
    public function findAll() {
        return $this->query("SELECT * FROM {$this->table}");
    }
    
    /**
     * ПОЛУЧЕНИЕ ОДНОЙ ЗАПИСИ ПО ID
     */
    public function find($id) {
        $id = (int)$id;
        $result = $this->query("SELECT * FROM {$this->table} WHERE id = $id");
        return $result[0] ?? null;
    }
}