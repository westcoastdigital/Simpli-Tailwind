const menuBtn = document.getElementById("menuBtn");
const mobileMenu = document.getElementById("mobileMenu");

menuBtn.addEventListener("click", () => {
  const opening = mobileMenu.classList.toggle("hidden") === false;

  menuIconOpen.classList.toggle("hidden", opening);
  menuIconClose.classList.toggle("hidden", !opening);
  menuBtn.setAttribute("aria-expanded", String(opening));
});

// function adjustTopNav() {
//   const adminBar = document.querySelector("#wpadminbar");
//   const topNav = document.querySelector("#TopNavBar");

//   if (document.body.classList.contains("admin-bar") && adminBar && topNav) {
//     topNav.style.top = adminBar.offsetHeight + "px";
//   }
// }

// window.addEventListener("load", adjustTopNav);
// window.addEventListener("resize", adjustTopNav);
