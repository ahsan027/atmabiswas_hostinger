document.addEventListener("DOMContentLoaded", function () {
  const currentLocation = location.href;
  console.log(location.href);
  const menuItems = document.querySelectorAll(".navbar .bottom-row a");

  menuItems.forEach((item) => {
    if (item.href === currentLocation) {
      item.classList.add("active");
    }
  });
});
document.addEventListener("DOMContentLoaded", function () {
  const currentLocation = location.href;
  console.log(location.href);
  const menuItems = document.querySelectorAll(".navbar .top-row .bars a");

  menuItems.forEach((item) => {
    if (item.href === currentLocation) {
      item.classList.add("active");
    }
  });
});
