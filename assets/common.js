document.addEventListener("DOMContentLoaded", function () {
  const toggles = document.querySelectorAll(".menu-toggle");

  toggles.forEach((toggle) => {
    toggle.addEventListener("click", function () {
      const submenu = this.nextElementSibling; // phần .submenu ngay sau button
      const arrow = this.querySelector(".lucide-chevron-down"); // mũi tên
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
});
