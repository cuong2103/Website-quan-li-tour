</div>
<?php if (!empty($message ?? '')): ?>
  <div id="simple-toast"
    class="fixed bottom-4 right-5 z-50 w-96 bg-white border border-gray-200 rounded-lg shadow-lg p-4 flex items-center justify-between animate-in fade-in slide-in-from-right duration-300">

    <div class="flex items-center gap-3">
      <!-- Icon tùy chọn (bỏ nếu không muốn) -->
      <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
      <span class="text-gray-800 font-medium"><?= htmlspecialchars($message) ?></span>
    </div>

    <!-- Nút đóng -->
    <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  <!-- Tự ẩn sau 5 giây -->
  <script>
    setTimeout(() => {
      const toast = document.getElementById('simple-toast');
      if (toast) toast.remove();
    }, 5000);
  </script>
<?php endif; ?>
</body>
<script src="<?= BASE_URL ?>assets/lucide.js"></script>
<script>
  lucide.createIcons();
</script>

</html>