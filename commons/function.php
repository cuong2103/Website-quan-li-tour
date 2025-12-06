<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
// Kết nối CSDL qua PDO
function connectDB()
{
    // Kết nối CSDL
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", DB_USERNAME, DB_PASSWORD);

        // cài đặt chế độ báo lỗi là xử lý ngoại lệ
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // cài đặt chế độ trả dữ liệu
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $conn;
    } catch (PDOException $e) {
        echo ("Connection failed: " . $e->getMessage());
    }
}

function uploadFile($file, $folderSave)
{
    $file_upload = $file;
    $pathStorage = $folderSave . rand(10000, 99999) . $file_upload['name'];

    $tmp_file = $file_upload['tmp_name'];
    $pathSave = PATH_ROOT . $pathStorage; // Đường dãn tuyệt đối của file

    if (move_uploaded_file($tmp_file, $pathSave)) {
        return $pathStorage;
    }
    return null;
}

function deleteFile($file)
{
    $pathDelete = PATH_ROOT . $file;
    if (file_exists($pathDelete)) {
        unlink($pathDelete); // Hàm unlink dùng để xóa file
    }
}

// Hàm debug
function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

//Xóa session sau khi load trang
function deleteSessionError()
{
    if (isset($_SESSION['flash'])) {
        unset($_SESSION['flash']);
        unset($_SESSION['error']);
    }
}

// Hàm check login 
function checkLogin()
{
    if (!isset($_SESSION['currentUser']["roles"])) {
        header("Location: " . BASE_URL . '?act=login');
        exit();
    }
}



function requireAdmin()
{
    // Kiểm tra đang login
    if (!isset($_SESSION['currentUser'])) {
        redirect("login");
    }

    // Kiểm tra trạng thái
    if ($_SESSION['currentUser']['status'] != 1) {
        Message::set("error", "Tài khoản đã bị khóa!");
        session_destroy();
        redirect("login");
    }

    if (($_SESSION['currentUser']['roles'] ?? '') !== 'admin') {
        Message::set("error", "403 - Chỉ Admin được phép truy cập!");
        redirect("403");
    }
}

function validate($data, $rules)
{
    $errors = [];

    foreach ($rules as $field => $ruleString) {
        $rulesArray = explode('|', $ruleString);

        foreach ($rulesArray as $rule) {
            if ($rule === 'required') {
                if (!isset($data[$field])) {
                    $errors[$field][] = "Trường này bắt buộc phải nhập.";
                } else {
                    // nếu là mảng
                    if (is_array($data[$field])) {
                        foreach ($data[$field] as $i => $value) {
                            if (trim((string)$value) === '') {
                                $errors[$field][$i][] = "Trường này bắt buộc phải nhập.";
                            }
                        }
                    } else {
                        if (trim($data[$field]) === '') {
                            $errors[$field][] = "Trường này bắt buộc phải nhập.";
                        }
                    }
                }
            } elseif ($rule === 'email') {
                if (isset($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "Trường này phải là email hợp lệ.";
                }
            } elseif (strpos($rule, 'min:') === 0) {
                $min = (int)explode(':', $rule)[1];
                if (isset($data[$field]) && strlen($data[$field]) < $min) {
                    $errors[$field][] = "Trường này phải có ít nhất $min ký tự.";
                }
            } elseif (strpos($rule, 'max:') === 0) {
                $max = (int)explode(':', $rule)[1];
                if (isset($data[$field]) && strlen($data[$field]) > $max) {
                    $errors[$field][] = "Trường này không được vượt quá $max ký tự.";
                }
            } elseif ($rule === 'numeric') {
                if (isset($data[$field]) && !is_numeric($data[$field])) {
                    $errors[$field][] = "Trường này phải là số.";
                }
            } elseif ($rule === 'array') {
                if (!isset($data[$field]) || !is_array($data[$field])) {
                    $errors[$field][] = "Trường này phải là mảng.";
                }
            } elseif (substr($rule, 0, 2) === '*') {
                $subRule = substr($rule, 2); // lấy rule bên trong
                if (isset($data[$field]) && is_array($data[$field])) {
                    foreach ($data[$field] as $i => $value) {
                        if ($subRule === 'required' && trim($value) === '') {
                            $errors[$field][$i][] = "Trường này bắt buộc phải nhập.";
                        }
                        if ($subRule === 'numeric' && !is_numeric($value)) {
                            $errors[$field][$i][] = "Phần tử $i của này phải là số.";
                        }
                    }
                }
            } elseif ($rule === 'time') {
                if (isset($data[$field]) && !preg_match('/^\d{1,2}:\d{2}$/', $data[$field])) {
                    $errors[$field][] = "Trường $field phải có định dạng giờ HH:MM.";
                }
            }
        }
    }

    return $errors;
}

// Hàm lấy giá trị cũ từ session (dùng khi validation thất bại)
function old($key, $default = '')
{
    if (isset($_SESSION['old'][$key])) {
        return htmlspecialchars($_SESSION['old'][$key]);
    }
    return $default;
}

function redirect($act)
{
    header("Location: " . BASE_URL . "?act=" . $act);
    exit();
}

function timeAgo($datetime)
{
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return 'Vừa xong';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' phút trước';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' giờ trước';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' ngày trước';
    } else {
        return date('d/m/Y H:i', $timestamp);
    }
}
// Tính tổng số ngày của tour
function calculateTotalDays($startDate, $endDate)
{
    $start = new DateTime($startDate);
    $end   = new DateTime($endDate);
    $end->setTime(0, 0, 0); // đảm bảo tính đúng ngày cuối
    $start->setTime(0, 0, 0);
    $diff = $start->diff($end);
    return $diff->days + 1; // +1 để tính cả ngày đầu và cuối
}

// Tính ngày hiện tại của tour (từ start_date)
function getCurrentDay($startDate, $endDate)
{
    $today = new DateTime(date('Y-m-d'));
    $start = new DateTime($startDate);
    $end   = new DateTime($endDate);
    $start->setTime(0, 0, 0);
    $end->setTime(0, 0, 0);

    if ($today < $start) return 0;      // chưa bắt đầu
    if ($today > $end) return calculateTotalDays($startDate, $endDate); // đã kết thúc

    $diff = $start->diff($today);
    return $diff->days + 1; // +1 ngày bắt đầu
}

// Lấy danh sách trạng thái booking
function getBookingStatuses()
{
    return [
        'pending' => 'Chờ thanh toán',
        'deposited' => 'Đã cọc',
        'paid' => 'Đã thanh toán đủ',
        'completed' => 'Hoàn thành Tour',
        'cancelled' => 'Đã hủy'
    ];
}

// Hiển thị badge trạng thái booking
function renderStatusBadge($status)
{
    switch ($status) {
        case 'pending': // Chờ thanh toán
            return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">Chờ thanh toán</span>';

        case 'deposited': // Đã cọc
            return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">Đã cọc</span>';

        case 'paid': // Đã thanh toán đủ
            return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-sky-100 text-sky-700">Đã thanh toán đủ</span>';

        case 'cancelled': // Đã hủy
            return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Đã hủy</span>';

        case 'completed': // Hoàn thành Tour
            return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Hoàn thành Tour</span>';

        default:
            return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">Không rõ</span>';
    }
}
