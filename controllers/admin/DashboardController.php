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
    // Biểu đồ doanh thu: lấy mảng total từ $recentRevenue
    $revenueChartData = array_column($recentRevenue, 'total');

    // Biểu đồ trạng thái booking: chuyển từ mảng associative sang mảng values
    // Thứ tự: pending, deposited, paid, cancelled, completed
    $bookingStatusChartData = [
      $bookingStatusStats['pending'] ?? 0,
      $bookingStatusStats['deposited'] ?? 0,
      $bookingStatusStats['paid'] ?? 0,
      $bookingStatusStats['cancelled'] ?? 0,
      $bookingStatusStats['completed'] ?? 0
    ];

    // Các biến sẽ được truyền sang view:
    // - $currentBookings, $bookingGrowth
    // - $currentRevenue, $revenueGrowth
    // - $revenueChartData, $bookingStatusChartData
    // - $pendingBookings

    require_once './views/admin/dashboard.php';
  }
}
