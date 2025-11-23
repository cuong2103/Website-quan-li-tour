<?php
class CategoryController
{
  public $categoryModel;

  public function __construct()
  {
    $this->categoryModel = new CategoryModel();
  }

  public function index()
  {
    $categories = $this->categoryModel->getAll();
    $tree = buildTree($categories);
    require_once './views/admin/categories/index.php';
  }
  public function store()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = [
        'name' => trim($_POST['name']),
        'parent_id' => ($_POST['parent_id'] == "" ? null : $_POST['parent_id']),
        'created_by' => $_SESSION['user']['id'],
      ];

      $rules = [
        'name' => 'required|min:3|max:50',
      ];
      $errors = validate($data, $rules);
      if (!empty($errors)) {
        $categories = $this->categoryModel->getAll();
        $tree = buildTree($categories);
        require_once './views/admin/categories/index.php';
        exit;
      } else {
        $this->categoryModel->create($data);
        Message::set("success", "Thêm thành công!");
        redirect("categories");
        exit;
      }
    }
  }
  public function edit()
  {
    $id = $_GET['id'];
    $category = $this->categoryModel->getById($id);
    $categories = $this->categoryModel->getAll();
    $tree = buildTree($categories);
    require_once './views/admin/categories/edit.php';
  }

  public function update()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = $_GET['id'];
      $name = trim($_POST['name']);
      $parent_id = ($_POST['parent_id'] == "" ? null : $_POST['parent_id']);

      $data = [
        'name' => $name,
        'parent_id' => $parent_id,
      ];

      $rules = [
        'name' => 'required|min:3|max:50',
      ];

      $errors = validate($data, $rules);
      if (!empty($errors)) {
        // Keep submitted values in $category so edit view shows them
        $category = [
          'id' => $id,
          'name' => $name,
          'parent_id' => $parent_id,
        ];
        $categories = $this->categoryModel->getAll();
        $tree = buildTree($categories);
        require_once './views/admin/categories/edit.php';
        exit;
      } else {
        $this->categoryModel->update($name, $parent_id, $id);
        Message::set("success", "Cập nhật thành công!");
        redirect("categories");
        exit;
      }
    }
  }
  public function delete()
  {
    if (!isset($_GET['id'])) {
      redirect('categories');
      exit;
    }
    $id = $_GET['id'];
    $category = $this->categoryModel->getById($id);
    if (!$category) {
      Message::set("error", "Danh mục không tồn tại");
      redirect("categories");
      exit;
    }

    if ($this->categoryModel->hasChildren($id)) {
      Message::set("error", "Không thể xóa danh mục cha!");
      redirect("categories");
      exit;
    }
    $this->categoryModel->delete($id);
    Message::set("success", "Xóa thành công!");
    redirect("categories");
    exit;
  }
}
