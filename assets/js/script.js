  const menuBtn = document.getElementById("menuBtn");
  const mobileMenu = document.getElementById("mobileMenu");

  menuBtn.addEventListener("click", () => {
    mobileMenu.classList.toggle("hidden");
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