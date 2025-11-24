<?php 
class user_managementController {
    public $model;

    public function __construct() {
        $this->model = new user_managementModel();
    }

    public function index() {
        $users = $this->model->getAll(); // đổi tên biến cho rõ nghĩa
        
        // Cách 1: Đúng nhất (file nằm trong folder User_management)
        require_once './views/admin/User_management/index.php';        
    }
}