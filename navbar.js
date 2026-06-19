document.addEventListener("DOMContentLoaded", function () {
  var currentHref = location.href;

  document.querySelectorAll(".navbar .bottom-row a").forEach(function (item) {
    if (item.href === currentHref) item.classList.add("active");
  });

  document.querySelectorAll(".navbar .top-row .bars a").forEach(function (item) {
    if (item.href === currentHref) item.classList.add("active");
  });
});
