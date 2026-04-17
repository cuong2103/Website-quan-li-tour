<?php
class TourController
{
  public $tourModel;
  public $policyModel;
  public $categoryModel;
  public $destinationModel;
  public $serviceModel;

  public function __construct()
  {
    requireAdmin();
    $this->tourModel = new TourModel();
    $this->policyModel = new PolicyModel();
    $this->categoryModel = new CategoryModel();
    $this->destinationModel = new DestinationModel();
    $this->serviceModel = new ServiceModel();
  }

  public function index()
  {
    // Lấy filter parameters từ GET
    $filters = [
      'name' => $_GET['name'] ?? '',
      'category_id' => $_GET['category_id'] ?? '',
      'status' => $_GET['status'] ?? '',
      'is_fixed' => $_GET['is_fixed'] ?? '',
      'duration' => $_GET['duration'] ?? '',
      'destination_id' => $_GET['destination_id'] ?? '',
      'min_price' => $_GET['min_price'] ?? '',
      'max_price' => $_GET['max_price'] ?? '',
    ];

    // Lấy tours với filter
    $tours = $this->tourModel->getAll($filters);

    // Lấy data cho dropdowns
    $categories = $this->categoryModel->getAll();
    $destinations = $this->destinationModel->getAll();

    // Tính giá min/max để set slider range
    $priceRange = $this->tourModel->getPriceRange();

    require_once './views/admin/tours/index.php';
  }

  public function create()
  {
    $policies = $this->policyModel->getAll();
    $categories = $this->categoryModel->getAll();
    $destinations = $this->destinationModel->getAll();
    $services = $this->serviceModel->getAll();
    $tree = buildTree($categories);

    // Lấy lỗi và dữ liệu cũ từ session (sau khi redirect)
    $errors = $_SESSION['validate_errors'] ?? [];
    $old = $_SESSION['old'] ?? [];
    unset($_SESSION['validate_errors'], $_SESSION['old']);

    require_once './views/admin/tours/create.php';
  }

  public function store()
  {
    $data = [
      'name' => $_POST['name'],
      'category_id' => $_POST['category_id'],
      'introduction' => $_POST['introduction'],
      'duration_days' => $_POST['duration_days'],
      'adult_price' => $_POST['adult_price'],
      'child_price' => $_POST['child_price'],
      'status' => $_POST['status'],
      'is_fixed' => isset($_POST['is_fixed']) ? 1 : 0,
      'destination_id' => $_POST['destination_id'] ?? [],
      'arrival_time' => $_POST['arrival_time'] ?? [],
      'departure_time' => $_POST['departure_time'] ?? [],
      'description' => $_POST['description'] ?? [],
      'policy_ids' => $_POST['policy_ids'] ?? [],
      'service_ids' => $_POST['service_ids'] ?? [],
      'created_by' => $_SESSION['currentUser']['id']
    ];

    $rules = [
      'name' => 'required|min:3|max:255',
      'category_id' => 'required',
      'introduction' => 'required|min:10',
      'duration_days' => 'required|numeric',
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
      $_SESSION['validate_errors'] = $errors;
      $_SESSION['old'] = $_POST; // Lưu raw POST để view khôi phục đúng
      redirect('tours-create');
      exit;
    }

    // Tạo tour và lấy ID
    $tourId = $this->tourModel->create($data);

    // Thêm lịch trình
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
        'description' => $descriptions[$i],
        'created_by' => $_SESSION['currentUser']['id']
      ]);
    }

    // Gắn policies
    $policy_ids = $_POST['policy_ids'];
    foreach ($policy_ids as $policy_id) {
      $this->tourModel->attachPolicy($tourId, $policy_id, $_SESSION['currentUser']['id']);
    }

    // Gắn services nếu là tour cố định
    if ($data['is_fixed'] && !empty($data['service_ids'])) {
      foreach ($data['service_ids'] as $service_id) {
        $this->tourModel->attachService($tourId, $service_id, $_SESSION['currentUser']['id']);
      }
    }

    Message::set("success", "Thêm tour thành công!");
    redirect("tours");
  }

  public function detail()
  {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) { Message::set('error', 'ID không hợp lệ!'); redirect('tours'); exit; }

    $tour = $this->tourModel->getById($id);
    if (!$tour) { Message::set('error', 'Tour không tồn tại!'); redirect('tours'); exit; }

    $itineraries = $this->tourModel->getItinerariesByTourId($id);
    $policies = $this->tourModel->getPoliciesByTourId($id);
    $services = $this->tourModel->getTourServices($id);
    require_once './views/admin/tours/detail.php';
  }

  public function edit()
  {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) { Message::set('error', 'ID không hợp lệ!'); redirect('tours'); exit; }

    $tour = $this->tourModel->getById($id);
    if (!$tour) { Message::set('error', 'Không tìm thấy tour!'); redirect('tours'); exit; }

    $itineraries = $this->tourModel->getItineraries($id);
    $tourPolicies = $this->tourModel->getTourPolicies($id);
    $tourPolicyIds = array_column($tourPolicies, 'id');

    $tourServices = $this->tourModel->getTourServices($id);
    $tourServiceIds = array_column($tourServices, 'id');

    $policies = $this->policyModel->getAll();
    $categories = $this->categoryModel->getAll();
    $destinations = $this->destinationModel->getAll();
    $services = $this->serviceModel->getAll();
    $tree = buildTree($categories);

    // Lấy lỗi và dữ liệu cũ từ session (sau khi redirect)
    $errors = $_SESSION['validate_errors'] ?? [];
    $old = $_SESSION['old'] ?? [];
    unset($_SESSION['validate_errors'], $_SESSION['old']);

    require_once './views/admin/tours/edit.php';
  }

  public function update()
  {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) { Message::set('error', 'ID không hợp lệ!'); redirect('tours'); exit; }

    $data = [
      'name' => $_POST['name'],
      'category_id' => $_POST['category_id'],
      'introduction' => $_POST['introduction'],
      'duration_days' => $_POST['duration_days'],
      'adult_price' => $_POST['adult_price'],
      'child_price' => $_POST['child_price'],
      'status' => $_POST['status'],
      'is_fixed' => isset($_POST['is_fixed']) ? 1 : 0,
      'destination_id' => $_POST['destination_id'] ?? [],
      'arrival_time' => $_POST['arrival_time'] ?? [],
      'departure_time' => $_POST['departure_time'] ?? [],
      'description' => $_POST['description'] ?? [],
      'policy_ids' => $_POST['policy_ids'] ?? [],
      'service_ids' => $_POST['service_ids'] ?? [],
    ];

    $rules = [
      'name' => 'required|min:3|max:255',
      'category_id' => 'required',
      'introduction' => 'required|min:10',
      'duration_days' => 'required|numeric',
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
      $_SESSION['validate_errors'] = $errors;
      $_SESSION['old'] = $_POST; // Lưu raw POST để view khôi phục đúng
      redirect('tours-edit&id=' . $id);
      exit;
    }

    // Cập nhật tour
    $this->tourModel->update($id, $data, $_SESSION['currentUser']['id']);

    // Xóa lịch trình cũ
    $this->tourModel->deleteItineraries($id);

    // Thêm lịch trình mới
    $destinationIds = $_POST['destination_id'];
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
        'description' => $descriptions[$i],
        'created_by' => $_SESSION['currentUser']['id']
      ]);
    }

    // Xóa policies cũ
    $this->tourModel->detachAllPolicies($id);

    // Gắn policies mới
    $policy_ids = $_POST['policy_ids'];
    foreach ($policy_ids as $policy_id) {
      $this->tourModel->attachPolicy($id, $policy_id, $_SESSION['currentUser']['id']);
    }

    // Xử lý services
    $this->tourModel->detachAllServices($id);
    if ($data['is_fixed'] && !empty($data['service_ids'])) {
      foreach ($data['service_ids'] as $service_id) {
        $this->tourModel->attachService($id, $service_id, $_SESSION['currentUser']['id']);
      }
    }

    Message::set("success", "Cập nhật tour thành công!");
    redirect("tours");
  }

  public function delete()
  {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) { Message::set('error', 'ID không hợp lệ!'); redirect('tours'); exit; }

    // Kiểm tra tour có booking đang active không
    if ($this->tourModel->hasActiveBookings($id)) {
      Message::set('error', 'Không thể xóa tour này vì đang có booking chưa hoàn thành!');
      redirect('tours');
      exit;
    }

    if ($this->tourModel->delete($id)) {
      Message::set('success', 'Xóa tour thành công!');
    } else {
      Message::set('error', 'Xóa tour thất bại, vui lòng thử lại!');
    }
    redirect('tours');
  }
}
