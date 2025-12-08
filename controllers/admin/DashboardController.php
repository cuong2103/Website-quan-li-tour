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
    // 1. Thống kê Booking & Doanh thu
    $monthlyStats = $this->bookingModel->getMonthlyStats();

    // Tính % tăng trưởng Booking
    $currentBookings = $monthlyStats['bookings']['current_month_count'];
    $lastBookings = $monthlyStats['bookings']['last_month_count'];
    $bookingGrowth = $lastBookings > 0 ? (($currentBookings - $lastBookings) / $lastBookings) * 100 : 100;

    // Tính % tăng trưởng Doanh thu
    $currentRevenue = $monthlyStats['revenue']['current_month_revenue'];
    $lastRevenue = $monthlyStats['revenue']['last_month_revenue'];
    $revenueGrowth = $lastRevenue > 0 ? (($currentRevenue - $lastRevenue) / $lastRevenue) * 100 : 100;



    // 4. Dữ liệu biểu đồ
    $recentRevenue = $this->bookingModel->getRecentRevenue();
    $bookingStatusStats = $this->bookingModel->getBookingStatusStats();

    // 5. Danh sách booking chờ xử lý
    $pendingBookings = $this->bookingModel->getPendingBookings(5);

    // Xử lý dữ liệu cho biểu đồ
    // Biểu đồ doanh thu: lấy mảng total và labels từ $recentRevenue
    $revenueChartData = array_column($recentRevenue, 'total');
    // Tạo labels động theo tháng thực tế
    $revenueChartLabels = array_map(function ($item) {
      $monthNum = (int) date('n', strtotime($item['month'] . '-01'));
      return 'T' . $monthNum;
    }, $recentRevenue);

    $bookingStatusChartData = [
      $bookingStatusStats['pending'] ?? 0,
      $bookingStatusStats['deposited'] ?? 0,
      $bookingStatusStats['paid'] ?? 0,
      $bookingStatusStats['cancelled'] ?? 0,
      $bookingStatusStats['completed'] ?? 0
    ];


    require_once './views/admin/dashboard.php';
  }
}
