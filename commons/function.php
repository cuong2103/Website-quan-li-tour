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
function checkLogin()
{
    if (!isset($_SESSION['currentUser']["roles"])) {
        header("Location: " . BASE_URL . '?act=login-admin');
        exit();
    }
}



function requireAdmin()
{
    if (!($_SESSION['currentUser']['roles'] ?? '') == 'admin') {
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

function redirect($act)
{
    header("Location: " . BASE_URL . "?act=" . $act);
    exit();
}
