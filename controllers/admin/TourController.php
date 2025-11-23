<?php
class TourController
{
  public $tourModel;

  public function __construct()
  {
    $this->tourModel = new TourModel();
  }
  public function index()
  {
    $tours = $this->tourModel->getAll();
    require_once './views/admin/tours/index.php';
  }
}
