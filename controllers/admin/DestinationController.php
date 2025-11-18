<?php
class DestinationController {
    public $modelDestination;

    public function __construct()
    {
        $this->modelDestination = new DestinationModel();
    }

    public function index(){
        $listDestination = $this->modelDestination->index();

        require_once './views/admin/destination.php';
    }
}