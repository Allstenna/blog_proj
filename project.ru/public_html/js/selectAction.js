document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll(".btn-select");

  buttons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.stopPropagation();

      const actionMenu = btn.parentElement.querySelector(".select-action");

      document.querySelectorAll(".select-action").forEach((menu) => {
        if (menu !== actionMenu) menu.classList.remove("active");
      });

      if (actionMenu) {
        actionMenu.classList.toggle("active");
      }
    });
  });
});