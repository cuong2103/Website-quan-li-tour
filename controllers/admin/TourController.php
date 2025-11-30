<?php
class TourController
{
  public $tourModel;
  public $policyModel;
  public $categoryModel;
  public $destinationModel;

  public function __construct()
  {
    requireAdmin();
    $this->tourModel = new TourModel();
    $this->policyModel = new PolicyModel();
    $this->categoryModel = new CategoryModel();
    $this->destinationModel = new DestinationModel();
  }
  public function index()
  {
    $tours = $this->tourModel->getAll();
    require_once './views/admin/tours/index.php';
  }

  public function create()
  {
    $policies = $this->policyModel->getAll();
    $categories = $this->categoryModel->getAll();
    $destinations = $this->destinationModel->getAll();
    $tree = buildTree($categories);
    require_once './views/admin/tours/create.php';
  }

  public function store()
  {
    $data = [
      'name' => $_POST['name'],
      'category_id' => $_POST['category_id'],
      'introduction' => $_POST['introduction'],
      'adult_price' => $_POST['adult_price'],
      'child_price' => $_POST['child_price'],
      'status' => $_POST['status'],
      'destination_id' => $_POST['destination_id'] ?? [],
      'arrival_time' => $_POST['arrival_time'],
      'departure_time' => $_POST['departure_time'],
      'description' => $_POST['description'],
      'policy_ids' => $_POST['policy_ids'] ?? [],
      'created_by' => $_SESSION['currentUser']['id']
    ];

    $rules = [
      'name' => 'required|min:3|max:100',
      'category_id' => 'required',
      'adult_price' => 'required|numeric',
      'child_price' => 'required|numeric',
      'destination_id' => 'required|array',
      'arrival_time' => 'required|array',
      'departure_time' => 'required|array',
      'description' => 'required|array',
      'policy_ids' => 'required|array',
    ];

    $errors = validate($data, $rules);

    if (!empty($errors)) {
      $policies = $this->policyModel->getAll();
      $categories = $this->categoryModel->getAll();
      $destinations = $this->destinationModel->getAll();
      $tree = buildTree($categories);
      require_once './views/admin/tours/create.php';
      exit;
    }

    $tourId = $this->tourModel->create($data);

    // Đơn giản hơn vì đã có destination_id sẵn
    $destinationIds = $_POST['destination_id'];
    $arrival_times = $_POST['arrival_time'];
    $departure_times = $_POST['departure_time'];
    $descriptions = $_POST['description'];

    for ($i = 0; $i < count($destinationIds); $i++) {
      $this->tourModel->addItinerary([
        'tour_id' => $tourId,
        'destination_id' => $destinationIds[$i],
        'order_number' => $i + 1,
        'arrival_time' => $arrival_times[$i],
        'departure_time' => $departure_times[$i],
        'description' => $descriptions[$i]
      ]);
    }

    $policy_ids = $_POST['policy_ids'];
    foreach ($policy_ids as $policy_id) {
      $this->tourModel->attachPolicy($tourId, $policy_id);
    }

    Message::set("success", "Thêm tour thành công!");
    redirect("tours");
  }

  public function detail()
  {
    $id = $_GET['id'];
    $tour = $this->tourModel->getById($id);
    $itineraries = $this->tourModel->getItinerariesByTourId($id);
    $policies = $this->tourModel->getPoliciesByTourId($id);
    require_once './views/admin/tours/detail.php';
  }

  public function edit()
  {
    $id = $_GET['id'];
    $tour = $this->tourModel->getById($id);
    if (!$tour) {
      Message::set("error", "Không tìm thấy tour!");
      redirect("tours");
    }

    $itineraries = $this->tourModel->getItineraries($id);

    $tourPolicies = $this->tourModel->getTourPolicies($id);
    $tourPolicyIds = array_column($tourPolicies, 'id');

    $policies = $this->policyModel->getAll();
    $categories = $this->categoryModel->getAll();
    $destinations = $this->destinationModel->getAll();
    $tree = buildTree($categories);

    require_once './views/admin/tours/edit.php';
  }

  public function update()
  {
    $id = $_GET['id'];

    $data = [
      'name' => $_POST['name'],
      'category_id' => $_POST['category_id'],
      'introduction' => $_POST['introduction'],
      'adult_price' => $_POST['adult_price'],
      'child_price' => $_POST['child_price'],
      'status' => $_POST['status'],
      'destination_id' => $_POST['destination_id'] ?? [], // ĐỔI TỪ destination_name
      'arrival_time' => $_POST['arrival_time'] ?? [],
      'departure_time' => $_POST['departure_time'] ?? [],
      'description' => $_POST['description'] ?? [],
      'policy_ids' => $_POST['policy_ids'] ?? [],
    ];

    $rules = [
      'name' => 'required|min:3|max:100',
      'category_id' => 'required',
      'adult_price' => 'required|numeric',
      'child_price' => 'required|numeric',
      'destination_id' => 'required|array', // ĐỔI TỪ destination_name
      'arrival_time' => 'required|array',
      'departure_time' => 'required|array',
      'description' => 'required|array',
      'policy_ids' => 'required|array',
    ];

    $errors = validate($data, $rules);

    if (!empty($errors)) {
      $tour = $this->tourModel->getById($id);
      $itineraries = $this->tourModel->getItineraries($id);
      $tourPolicies = $this->tourModel->getTourPolicies($id);
      $tourPolicyIds = array_column($tourPolicies, 'id');
      $policies = $this->policyModel->getAll();
      $categories = $this->categoryModel->getAll();
      $destinations = $this->destinationModel->getAll(); // THÊM DÒNG NÀY
      $tree = buildTree($categories);
      require_once './views/admin/tours/edit.php';
      exit;
    }

    // Đơn giản hơn vì đã có destination_id sẵn từ select
    $destinationIds = $_POST['destination_id'];

    // Cập nhật tour
    $this->tourModel->update($id, $data);

    // Xóa lịch trình cũ
    $this->tourModel->deleteItineraries($id);

    // Thêm lịch trình mới
    $arrival_times = $_POST['arrival_time'];
    $departure_times = $_POST['departure_time'];
    $descriptions = $_POST['description'];

    for ($i = 0; $i < count($destinationIds); $i++) {
      $this->tourModel->addItinerary([
        'tour_id' => $id,
        'destination_id' => $destinationIds[$i],
        'order_number' => $i + 1,
        'arrival_time' => $arrival_times[$i],
        'departure_time' => $departure_times[$i],
        'description' => $descriptions[$i]
      ]);
    }

    // Xóa policies cũ
    $this->tourModel->detachAllPolicies($id);

    // Gắn policies mới
    $policy_ids = $_POST['policy_ids'];
    foreach ($policy_ids as $policy_id) {
      $this->tourModel->attachPolicy($id, $policy_id);
    }

    Message::set("success", "Cập nhật tour thành công!");
    redirect("tours");
  }

  public function delete()
  {
    $id = $_GET['id'];
    $this->tourModel->delete($id);
    Message::set("success", "Xóa tour thành công!");
    redirect("tours");
  }
}
