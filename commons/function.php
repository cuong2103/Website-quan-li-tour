<?php

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
function checkLoginAdmin()
{
    if (!isset($_SESSION['user']["role_id"])) {
        header("Location: " . BASE_URL . '?act=login-admin');
        exit();
    }
}

function checkLoginGuide()
{
    if (!isset($_SESSION['user']["role_id"])) {
        header("Location: " . BASE_URL . '?act=login-guide');
    }
}


function validate($data, $rules)
{
    $errors = [];

    foreach ($rules as $field => $ruleString) {
        $rulesArray = explode('|', $ruleString);

        foreach ($rulesArray as $rule) {
            if ($rule === 'required') {
                if (!isset($data[$field]) || trim($data[$field]) === '') {
                    $errors[$field][] = "Trường $field là bắt buộc.";
                }
            } elseif ($rule === 'email') {
                if (isset($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "Trường $field phải là email hợp lệ.";
                }
            } elseif (strpos($rule, 'min:') === 0) {
                $min = (int)explode(':', $rule)[1];
                if (isset($data[$field]) && strlen($data[$field]) < $min) {
                    $errors[$field][] = "Trường $field phải có ít nhất $min ký tự.";
                }
            } elseif (strpos($rule, 'max:') === 0) {
                $max = (int)explode(':', $rule)[1];
                if (isset($data[$field]) && strlen($data[$field]) > $max) {
                    $errors[$field][] = "Trường $field không được vượt quá $max ký tự.";
                }
            }
        }
    }

    return $errors;
}

function redirect($act)
{
    header("Location: " . BASE_URL . "?act=" . $act);
    exit();
}
