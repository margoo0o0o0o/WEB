<?php
namespace App\Models;

use Core\Model;

class University extends Model {
    protected $table = 'universities';
    
    public function getAll() {
        return $this->findAll();
    }
    
    public function getWithFilters($search, $country, $sort, $order, $limit, $offset) {
        $where = "WHERE 1=1";
        if (!empty($search)) {
            $search = mysqli_real_escape_string($this->db, $search);
            $where .= " AND name LIKE '%$search%'";
        }
        if (!empty($country)) {
            $country = mysqli_real_escape_string($this->db, $country);
            $where .= " AND country = '$country'";
        }
        
        $allowedSort = ['name', 'country', 'price', 'rating'];
        $sort = in_array($sort, $allowedSort) ? $sort : 'name';
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        
        $sql = "SELECT * FROM {$this->table} $where ORDER BY $sort $order LIMIT $limit OFFSET $offset";
        return $this->query($sql);
    }
    
    public function getCount($search, $country) {
        $where = "WHERE 1=1";
        if (!empty($search)) {
            $search = mysqli_real_escape_string($this->db, $search);
            $where .= " AND name LIKE '%$search%'";
        }
        if (!empty($country)) {
            $country = mysqli_real_escape_string($this->db, $country);
            $where .= " AND country = '$country'";
        }
        $result = mysqli_query($this->db, "SELECT COUNT(*) as total FROM {$this->table} $where");
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }
    
    public function getCountries() {
        $result = mysqli_query($this->db, "SELECT DISTINCT country FROM {$this->table} ORDER BY country");
        $countries = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $countries[] = $row['country'];
        }
        return $countries;
    }
}