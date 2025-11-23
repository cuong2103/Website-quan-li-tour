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
      $name = trim($_POST['name']);
      $parent_id = ($_POST['parent_id'] == "" ? null : $_POST['parent_id']);
      $created_by = $_SESSION['user']['id'];
      $this->categoryModel->create($name, $parent_id, $created_by);
      $categories = $this->categoryModel->getAll();
      $tree = buildTree($categories);
      Message::set("success", "Thêm thành công!");
      require_once './views/admin/categories/index.php';
      exit;
    }
    header("Location:" . BASE_URL . "?act=categories");
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
      $this->categoryModel->update($name, $parent_id, $id);
      Message::set("success", "Cập nhật thành công!");
      header("Location:" . BASE_URL . "?act=categories");
      exit;
    }
  }
  public function delete()
  {
    $id = $_GET['id'];
    $children = $this->categoryModel->hasChildren($id);
    if ($children) {
      $categories = $this->categoryModel->getAll();
      Message::set("error", "không thể xóa cha");
      $tree = buildTree($categories);
      require_once './views/admin/categories/index.php';
      exit;
    }
    Message::set("success", "Xóa thành công rồi nhé");
    $this->categoryModel->delete($id);
    header("Location:" . BASE_URL . "?act=categories");
  }
}
