document.addEventListener("DOMContentLoaded", function () {
  lucide.createIcons();
  const toggles = document.querySelectorAll(".menu-toggle");

  toggles.forEach((toggle) => {
    toggle.addEventListener("click", function () {
      const submenu = this.nextElementSibling; // phần .submenu ngay sau button
      const arrow = this.querySelector("svg.arrow"); // mũi tên

      // Toggle class active
      this.classList.toggle("text-indigo-700");
      this.classList.toggle("bg-indigo-50");

      // Mở/đóng submenu
      if (submenu.style.maxHeight && submenu.style.maxHeight !== "0px") {
        submenu.style.maxHeight = "0px";
        arrow.classList.remove("rotate-180");
      } else {
        submenu.style.maxHeight = submenu.scrollHeight + "px";
        arrow.classList.add("rotate-180");
      }
    });
  });

  document.addEventListener("click", function (e) {
    const row = e.target.closest(".cat-row");
    if (!row) return; // không phải click vào cat-row → bỏ qua

    const currentPadding = parseInt(row.style.paddingLeft) || 0;

    let sibling = row.nextElementSibling;
    let hiddenCount = 0;

    // Duyệt tất cả anh em kế tiếp có padding > currentPadding → ẩn/hiện chúng
    while (sibling) {
      const siblingPadding = parseInt(sibling.style.paddingLeft) || 0;
      if (siblingPadding <= currentPadding) break; // gặp cấp bằng hoặc cao hơn → dừng

      if (sibling.classList.toggle("hidden")) hiddenCount++;
      sibling = sibling.nextElementSibling;
    }
  });

  // Mở sẵn vài cấp để đẹp
  setTimeout(() => {
    document.querySelectorAll(".cat-row")[0].click();
    document.querySelectorAll(".cat-row")[1].click();
  }, 100);

  function showAlert(message, duration = 3000) {
    const alertBox = document.getElementById("alert-message");
    alertBox.textContent = message;
    alertBox.classList.remove("opacity-0"); // hiện alert
    alertBox.classList.add("opacity-100");

    // tự ẩn sau duration
    setTimeout(() => {
      alertBox.classList.remove("opacity-100");
      alertBox.classList.add("opacity-0");
    }, duration);
  }
});
