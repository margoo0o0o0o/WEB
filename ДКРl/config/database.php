<?php
/**
 * КЛАСС ПОДКЛЮЧЕНИЯ К БАЗЕ ДАННЫХ
 * 
 * Используется паттерн Singleton (Одиночка)
 * Гарантирует, что будет только одно подключение к БД
 */

namespace Config;

class Database {
    
    /**
     * @var Database|null $instance — единственный экземпляр класса
     * @var object $conn — подключение к БД
     */
    private static $instance = null;
    private $conn;
    
    /**
     * Настройки подключения к БД
     */
    private $host = '127.0.0.1';
    private $user = 'root';
    private $password = '05rn05';        // ТВОЙ ПАРОЛЬ!
    private $dbname = 'study_moov';
    
    /**
     * Приватный конструктор — нельзя создать объект через new
     */
    private function __construct() {
        $this->conn = mysqli_connect(
            $this->host,
            $this->user,
            $this->password,
            $this->dbname
        );
        
        if (!$this->conn) {
            die('Ошибка подключения к БД: ' . mysqli_connect_error());
        }
        
        mysqli_set_charset($this->conn, 'utf8');
    }
    
    /**
     * Получение экземпляра класса (Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Получение подключения к БД
     */
    public function getConnection() {
        return $this->conn;
    }
}