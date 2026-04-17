<?php
class DashboardController
{
  public $userModel;
  public $bookingModel;
  public $tourModel;
  public $customerModel;

  public function __construct()
  {
    requireAdmin();
    $this->userModel = new UserModel();
    $this->bookingModel = new BookingModel();
    $this->tourModel = new TourModel();
    $this->customerModel = new CustomerModel();
  }

  public function Dashboard()
  {
    // 5 chỉ số theo yêu cầu
    $revenueSummary = $this->bookingModel->getRevenueSummary((int)date('Y'));
    $revenueToday = (float)($revenueSummary['today'] ?? 0);
    $revenueMonth = (float)($revenueSummary['month'] ?? 0);
    $revenueYear = (float)($revenueSummary['year'] ?? 0);

    $bookingsToday = (int)$this->bookingModel->getBookingsToday();
    $newCustomersToday = (int)$this->customerModel->getNewCustomersToday();

    // Booking đang chờ xử lí
    $pendingBookings = $this->bookingModel->getPendingBookings(8);

    // Biểu đồ doanh thu theo ngày (mặc định tháng/năm hiện tại; thay đổi bằng AJAX)
    $selectedMonth = (int)date('n');
    $selectedYear = (int)date('Y');
    $dailyRevenue = $this->bookingModel->getRevenueByDay($selectedMonth, $selectedYear);
    $revenueChartData = $dailyRevenue['data'] ?? [];
    $revenueChartLabels = $dailyRevenue['labels'] ?? [];
    $selectedMonth = (int)($dailyRevenue['month'] ?? $selectedMonth);
    $selectedYear = (int)($dailyRevenue['year'] ?? $selectedYear);


    require_once './views/admin/dashboard.php';
  }

  public function RevenueData()
  {
    header('Content-Type: application/json; charset=utf-8');

    $month = isset($_POST['month']) ? (int)$_POST['month'] : 0;
    $year = isset($_POST['year']) ? (int)$_POST['year'] : 0;

    $data = $month === 0
      ? $this->bookingModel->getRevenueByMonth($year)
      : $this->bookingModel->getRevenueByDay($month, $year);

    echo json_encode([
      'ok' => true,
      'labels' => $data['labels'] ?? [],
      'data' => $data['data'] ?? [],
      'month' => $data['month'] ?? null,
      'year' => $data['year'] ?? null,
    ], JSON_UNESCAPED_UNICODE);
    exit();
  }
}
