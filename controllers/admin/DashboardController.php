<?php
class DashboardController
{
  public $useModel;

  public function __construct()
  {
    $this->useModel = new UserModel();
  }
  public function Dashboard()
  {
    $user = $this->useModel->getAll();
    // dd($user);
    require_once './views/admin/dashboard.php';
  }
}
