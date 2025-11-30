</div>
<?php if ($success = Message::get('success')): ?>
  <div id="simple-toast"
    class="fixed top-[6.5rem] right-5 z-50 w-96 bg-white border-[2px] border-green-200 rounded-lg shadow-lg p-4 pb-0 flex flex-col gap-2
           opacity-0 translate-x-8 transition-all duration-300 ease-out">

    <div class="flex items-center justify-between gap-3">
      <div class="flex items-center gap-3">
        <i class="w-6 h-6 text-green-600" data-lucide="check"></i>
        <span class="text-green-500 font-medium"><?= $success ?></span>
      </div>

      <!-- Nút đóng -->
      <button onclick="this.closest('#simple-toast').remove()" class="text-gray-400 hover:text-gray-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Thanh countdown -->
    <div class="h-1 w-full -translate-x-4 rounded-full overflow-hidden">
      <div id="toast-progress" class="h-1 bg-green-200 w-full transition-all"></div>
    </div>
  </div>

  <script>
    {
      const toast = document.getElementById('simple-toast');
      const progress = document.getElementById('toast-progress');

      if (toast && progress) {
        // Hiện toast
        setTimeout(() => {
          toast.classList.remove('opacity-0', 'translate-x-8');
          toast.classList.add('opacity-100', 'translate-x-0');
        }, 10);
        setTimeout(() => {
          toast.classList.remove('opacity-100', 'translate-x-0');
          toast.classList.add('opacity-0', 'translate-x-8');
        }, 4800);

        // Thanh progress countdown
        progress.style.transition = "width 5s linear";
        setTimeout(() => {
          progress.style.width = "0%";
        }, 20);

        // Tự biến mất sau 5 giây
        setTimeout(() => {
          toast.classList.add('opacity-0', 'translate-x-8');
          toast.addEventListener('transitionend', () => toast.remove());
        }, 5000);
      }
    }
  </script>
<?php endif; ?>

<?php if ($error = Message::get('error')): ?>
  <div id="simple-toast"
    class="fixed top-[6.5rem] right-5 z-50 w-96 bg-white border-[2px] border-red-200 rounded-lg shadow-lg p-4 pb-0 flex flex-col gap-2
              opacity-0 translate-x-8 transition-all duration-300 ease-out">

    <div class="flex items-center justify-between gap-3">
      <div class="flex items-center gap-3">
        <i class="w-6 h-6 text-red-600" data-lucide="x"></i>
        <span class="text-red-500 font-medium"><?= $error ?></span>
      </div>
      <!-- Nút đóng -->
      <button onclick="this.closest('#simple-toast').remove()" class="text-gray-400 hover:text-gray-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Thanh countdown -->
    <div class="h-1 w-full -translate-x-4 rounded-full overflow-hidden">
      <div id="toast-progress" class="h-1  bg-red-200 w-full transition-all"></div>
    </div>
  </div>

  <script>
    const toast = document.getElementById('simple-toast');
    const progress = document.getElementById('toast-progress');

    if (toast && progress) {
      // Hiện toast
      setTimeout(() => {
        toast.classList.remove('opacity-0', 'translate-x-8');
        toast.classList.add('opacity-100', 'translate-x-0');
      }, 10);
      setTimeout(() => {
        toast.classList.remove('opacity-100', 'translate-x-0');
        toast.classList.add('opacity-0', 'translate-x-8');
      }, 4800);

      // Animate thanh countdown 5s
      progress.style.transition = "width 5s linear";
      setTimeout(() => {
        progress.style.width = "0%";
      }, 20);

      // Tự ẩn toast sau 5s
      setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-x-8');
        toast.addEventListener('transitionend', () => toast.remove());
      }, 5000);
    }
  </script>
<?php endif; ?>



</body>
<script src="<?= BASE_URL ?>assets/lucide.js"></script>
<script>
  lucide.createIcons();
</script>

</html>