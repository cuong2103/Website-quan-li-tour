document.addEventListener("DOMContentLoaded", function () {
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
