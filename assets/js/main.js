const menuToggle = document.querySelector("[data-menu-toggle]");
const mobileMenu = document.querySelector("[data-mobile-menu]");
const mobileMenuPanel = document.querySelector("[data-mobile-menu-panel]");
const mobileMenuBackdrop = document.querySelector("[data-mobile-menu-backdrop]");
if (menuToggle && mobileMenu && mobileMenuPanel && mobileMenuBackdrop) {
  const closeMenu = () => {
    mobileMenu.classList.add("pointer-events-none");
    mobileMenuBackdrop.classList.add("opacity-0");
    mobileMenuPanel.classList.add("-translate-x-full");
    menuToggle.setAttribute("aria-expanded", "false");
    document.body.classList.remove("overflow-hidden");
  };
  const openMenu = () => {
    mobileMenu.classList.remove("pointer-events-none");
    mobileMenuBackdrop.classList.remove("opacity-0");
    mobileMenuPanel.classList.remove("-translate-x-full");
    menuToggle.setAttribute("aria-expanded", "true");
    document.body.classList.add("overflow-hidden");
  };
  menuToggle.addEventListener("click", () => {
    const isOpen = menuToggle.getAttribute("aria-expanded") === "true";
    if (isOpen) {
      closeMenu();
      return;
    }
    openMenu();
  });
  mobileMenuBackdrop.addEventListener("click", closeMenu);
  mobileMenu.addEventListener("click", (event) => {
    const target = event.target;
    if (!(target instanceof HTMLElement)) {
      return;
    }
    if (target.closest("a")) {
      closeMenu();
    }
  });
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      closeMenu();
    }
  });
  window.addEventListener("resize", () => {
    if (window.innerWidth >= 1024) {
      closeMenu();
    }
  });
}
//# sourceMappingURL=main.js.map
