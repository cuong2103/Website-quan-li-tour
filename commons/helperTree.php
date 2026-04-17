<?php
function buildTree(array $elements, $parentId = 0)
{
  $branch = [];

  foreach ($elements as $element) {
    if ($element['parent_id'] == $parentId) {
      $children = buildTree($elements, $element['id']);
      if ($children) {
        $element['children'] = $children;
      }
      $branch[] = $element;
    }
  }
  return $branch;
}

function renderCategory($categories, $level = 0)
{
  foreach ($categories as $cat) {
    $padding = 12 + $level * 24;

    echo '<div class=" flex items-center gap-2 py-2 px-3 hover:bg-gray-100 rounded-lg transition-colors group" style="padding-left:' . $padding . 'px;">';
    echo '<i class="w-4 h-4 text-blue-500 flex-shrink-0" data-lucide="folder"></i>';
    echo '<span class="flex-1 text-gray-900"> ' . $cat['name'] . '</span>';
    echo isset($_GET['id']) ? "" : '<div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
        <a href="' . BASE_URL . '?act=categories-edit&id=' . $cat['id'] . '" class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors" title="Sửa">
            <i class="w-4 h-4" data-lucide="square-pen"></i>
            <span class="text-xs font-semibold">Sửa</span>
        </a>
        <a href="' . BASE_URL . '?act=categories-delete&id=' . $cat['id'] . '" onclick="return confirm(\'Bạn có chắc muốn xoá không?\')" class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors" title="Xóa">
            <i class="w-4 h-4" data-lucide="trash-2"></i>
            <span class="text-xs font-semibold">Xóa</span>
        </a>
    </div>';
    echo '</div>';

    // Nếu có con → vẽ tiếp
    if (!empty($cat['children'])) {
      renderCategory($cat['children'], $level + 1);
    }
  }
}


function renderOption($tree, $parentName = '', $id = null)
{
  foreach ($tree as $cat) {
    // Tạo đường dẫn "cha → con"
    $fullName = $parentName
      ? $parentName . " → " . $cat['name']
      : $cat['name'];

    echo '<option value="' . $cat['id'] . '"'
      . ($cat['id'] == $id ? "selected " : "")
      . '>'
      . $fullName
      . '</option>';

    // Nếu có children thì đệ quy tiếp
    if (!empty($cat['children'])) {
      renderOption($cat['children'], $fullName, $id);
    }
  }
}

function getChildIds($categories, $id)
{
  $ids = [];
  foreach ($categories as $cat) {
    if ($cat['id'] == $id) {
      $ids[] = $cat['id'];
      if (!empty($cat['children'])) {
        foreach ($cat['children'] as $child) {
          $ids = array_merge($ids, getChildIds([$child], $child['id']));
        }
      }
    } elseif (!empty($cat['children'])) {
      $ids = array_merge($ids, getChildIds($cat['children'], $id));
    }
  }
  return $ids;
}
