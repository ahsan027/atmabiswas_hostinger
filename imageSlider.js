(function () {
  const slider = document.querySelector(".slider .list");
  const items  = document.querySelectorAll(".slider .list .item");
  const next   = document.getElementById("next");
  const prev   = document.getElementById("prev");
  const dots   = document.querySelectorAll(".slider .dots li");

  let active = 0;
  const last = items.length - 1;
  let autoPlay;

  function showSlide(n) {
    active = n;
    // Use window.innerWidth instead of reading offsetLeft — avoids forced layout reflow
    slider.style.transform = "translateX(-" + (active * window.innerWidth) + "px)";
    document.querySelector(".slider .dots li.active").classList.remove("active");
    dots[active].classList.add("active");
    restartAutoPlay();
  }

  function advance() {
    showSlide(active < last ? active + 1 : 0);
  }

  function restartAutoPlay() {
    clearInterval(autoPlay);
    autoPlay = setInterval(advance, 3000);
  }

  next.addEventListener("click", function () { showSlide(active < last ? active + 1 : 0); });
  prev.addEventListener("click", function () { showSlide(active > 0   ? active - 1 : last); });

  dots.forEach(function (dot, i) {
    dot.addEventListener("click", function () { showSlide(i); });
  });

  // Recalculate position on resize without reading offsetLeft
  window.addEventListener("resize", function () {
    slider.style.transform = "translateX(-" + (active * window.innerWidth) + "px)";
  });

  restartAutoPlay();
}());
